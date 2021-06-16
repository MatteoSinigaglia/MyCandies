<?php


namespace MyCandies\Controllers;

require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'dbh.php';
require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'Exceptions'.DS.'DBException.php';
require_once MYCANDIES_PATH.DS.'Tables'.DS.'Table.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'User.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Cart.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Product.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'ProductInCart.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Transaction.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';

use DB\dbh;
use DB\Exceptions\DBException;
use MyCandies\Entities;
use MyCandies\Entities\User;
use MyCandies\Entities\Cart;
use MyCandies\Entities\Product;
use MyCandies\Entities\ProductInCart;
use MyCandies\Entities\Transaction;
use MyCandies\Tables\Table;
use MyCandies\Controllers\Authentication;


class ShopManager {

	private $user;
	private $cart;
	private $productsInCart;

	private $dbh;

	private $users;
	private $carts;
	private $products;
	private $productsInCarts;

	public function __construct() {

		Authentication::initSession();
		$this->dbh = new dbh();
	}

	private function initUsers() {
		$this->users = new Table($this->dbh, 'Customers', 'id', User::class, [Entities\DB]);
	}

//	private function initCarts() {
//		$this->carts = new Table($this->dbh, 'Carts', 'id', Cart::class, [Entities\DB]);
//	}

	private function initProducts() {
		$this->products = new Table($this->dbh, 'Products', 'id', Product::class, [Entities\DB]);
	}

	private function initProductsInCarts() {
		$this->productsInCarts = new Table($this->dbh, 'ProductsInCarts', 'id', ProductInCart::class, [Entities\DB]);
	}

	public function addToCart(array $product) {
//		if (!isset($_SESSION['cart'])) {
//			$cart = new Cart();
//			$_SESSION['cart'] = serialize($cart);
//		}

		if (!isset($_SESSION['cart']['info']))
			$_SESSION['cart']['info'] = new Cart();

		if (isset($_SESSION['cart'][(int)$product['id']])) {
			$this->increaseProductQuantity((int)$product['id']);
		} else {
			$productInfo = $this->getProductById((int)$product['id']);
			$_SESSION['cart']['info']->addPriceToTotal((isset($productInfo) ? $productInfo->getPrice() : 0));
			$_SESSION['cart'][(int)$product['id']] = 1;
		}
	}

	public function increaseProductQuantity(int $productId) {
		if (isset($_SESSION['cart'][$productId])) {
			($_SESSION['cart']['info'])->addPriceToTotal((float)($this->getProductById($productId)->getPrice()));
			$_SESSION['cart'][$productId] += 1;
		}
	}

	public function decreaseProductQuantity(int $productId) {
		if ($_SESSION['cart'][$productId] <= 1)
			$this->removeProduct($productId);
		else {
			$_SESSION['cart']['info']->removePriceFromTotal((float)($this->getProductById($productId)->getPrice()));
			$_SESSION['cart'][$productId] -= 1;
		}
	}

	public function removeProduct(int $productId) {
		$this->removeToTotal((float)($this->getProductById($productId)->getPrice())*(int)$_SESSION['cart'][$productId]);
		unset($_SESSION['cart'][$productId]);
	}

	public function addToTotal(float $price) {
		require_once MYCANDIES_PATH.DS.'Entities'.DS.'Cart.php';
		if ($_SESSION['cart']['info']) {
			$cart = $_SESSION['cart']['info'];
			$cart->addToTotal($price);
		}
	}

	public function removeToTotal(float $price) {
		if (isset($_SESSION['cart']['info'])) {
			$cart = $_SESSION['cart']['info'];
			$cart->removePriceFromTotal($price);
		}
	}

	public function getCart() : ?array {
		return (isset($_SESSION['cart']) ? $_SESSION['cart'] : null);
	}

	public function getProductById(int $id): Product {
		if (!isset($this->products))
			$this->initProducts();

		try {
			$this->dbh->connect();
			$product = $this->products->find(['column' => 'id', 'value' => $id]);
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}
		return $product[0];
	}

	public function getProducts() : ?array {
		if (!isset($_SESSION['cart']) || count($_SESSION['cart']) < 2)
//			Cart not set or empty
			return null;

		$productsInCart = $_SESSION['cart'];
		unset($productsInCart['info']);

		if (!isset($this->products))
			$this->initProducts();

		$products = array();
		try {
			$this->dbh->connect();
			foreach ($productsInCart as $id => $quantity) {
				array_push($products, $this->getProductById($id));
			}
		} catch (DBException $e) {
			echo $e;
		} finally {
			$this->dbh->disconnect();
		}

		return $products;
	}

	public function checkout(array $cart, Authentication $auth) {

		try {
			$this->dbh->connect();
			$this->dbh->transactionStart();

			$cartId = $cart['info']->insert($this->dbh);
			unset($cart['info']);
			foreach ($cart as $productId => $productQuantity) {
				$productInCart = new ProductInCart(Entities\SHOP_MANAGER, [
					'product_id'    =>  $productId,
					'cart_id'       =>  $cartId,
					'quantity'      =>  $productQuantity]);
				$productInCart->insert($this->dbh);

//				New availability = product availability - product quantity
				$product = Product::getProductFromId($this->dbh, $productId);
				$product->updateAvailability($this->dbh, $product->getAvailability() - $productQuantity);
			}

			$transaction = new Transaction([
				'customer_id'   =>  $auth->getUserId(),
				'cart_id'       =>  $cartId,
				'address_id'    =>  $auth->getAddressId()
			]);

			$transaction->insert($this->dbh);
			$this->dbh->transactionCommit();
			unset($_SESSION['cart']);

		} catch (DBException $e) {
			$this->dbh->transactionRollback();
		} finally {
			$this->dbh->disconnect();
		}
	}
}