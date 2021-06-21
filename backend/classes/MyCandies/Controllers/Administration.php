<?php


namespace MyCandies\Controllers;

require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'paths.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'User.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Cart.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Transaction.php';
use DB\dbh;
use DB\Exceptions\DBException;
use MyCandies\Entities;
use MyCandies\Entities\Admin;
use MyCandies\Entities\User;
use MyCandies\Entities\Cart;
use MyCandies\Entities\Transaction;
use MyCandies\Tables\Table;

class Administration {

	private $users;
	private $dbh;

	public function __construct() {

		require_once __DIR__.'/../../DB/dbh.php';
		$this->dbh = new dbh();

		require_once __DIR__.'/../Tables/Table.php';
		$this->users = new Table($this->dbh, 'Customers', 'id', User::class, [Entities\DB]);
	}

	public function getUsers() : array {
		try {
			$this->dbh->connect();
			$users = $this->users->find();
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
		foreach ($users as $key => $user)
			if ($user->getEmail() == $_SESSION['email']) {
				unset($users[$key]);
				break;
			}
		return $users;
	}

	public function deleteUser(string $userEmail) {
        $admins = new Table($this->dbh, 'Admins', 'user_id', Admin::class);
		try {
			$this->dbh->connect();
			$user = $this->users->find(['column' => 'email', 'value' => $userEmail])[0];
			$admin = $admins->findById($user->getId());
			if ($admin === false) {
                $this->users->deleteWhere('email', $userEmail);
                $_SESSION['log'] = 'Utente eliminato.';
                $_SESSION['logtype'] = 'success';
            } else {
                $_SESSION['log'] = 'Impossibile rimuovere un admin';
                $_SESSION['logtype'] = 'failure';
            }
		} catch (DBException $e) {
            $_SESSION['log'] = 'Errore durante l\'eliminazione dell\'utente, riprovare più tardi.';
            $_SESSION['logtype'] = 'failure';
			throw $e;
		} finally {
			$this->dbh->disconnect();
		}
	}

	public function makeAdmin(string $userEmail) {
		require_once __DIR__.'/../Entities/Admin.php';
		$admins = new Table($this->dbh, 'Admins', 'user_id', Admin::class);
		try {
			$this->dbh->connect();
			$newAdmin = $this->users->find(['column' => 'email', 'value' => $userEmail]);
            $admin = new Admin($newAdmin[0]->getId());
            error_log('Admin columns: '.implode(', ', $admin->getColumns()));
            error_log('Admin values: '.implode(', ', $admin->getValues()));
			$admins->insert($admin);
            $_SESSION['log'] = 'Admin aggiunto.';
            $_SESSION['logtype'] = 'success';
		} catch (DBException $e) {

            $_SESSION['log'] = 'Errore durante l\'aggiunta dell\' admin.';
            $_SESSION['logtype'] = 'failure';
            throw new DBException($e->getMessage() . print_r($newAdmin)."\nUser id: ".$newAdmin[0]->getId());
		} finally {
			$this->dbh->disconnect();
		}
	}

	public function getTransactionsData() {
		try {
			$this->dbh->connect();
			$transactions = Transaction::getAllTransactions($this->dbh);
			$transactionsData = [];
			foreach ($transactions as $transaction) {
				array_push($transactionsData, $this->getTransactionData($transaction));
			}
			return $transactionsData;
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
	}

	private function getTransactionData(Transaction $transaction): array {
		$user = User::getFromId($this->dbh, $transaction->getCustomerId());
		$cart = Cart::getFromId($this->dbh, $transaction->getCartId());
		return [
			'user'      =>  $user->getEmail(),
			'cartId'    =>  $cart->getId(),
			'datetime'  =>  $transaction->getDatetime(),
			'total'     =>  $cart->getTotal()
		];
	}

	public function isAdmin(int $userId): bool {
        try {
            $this->dbh->connect();
            $admins = Admin::selectAll($this->dbh);
            $adminsIds = [];
            foreach ($admins as $admin) {
                array_push($adminsIds, $admin['user_id']);
            }
            return in_array($userId, $adminsIds);
        } catch (DBException $e) {
            return false;
        } finally {
            $this->dbh->disconnect();
        }
    }
}