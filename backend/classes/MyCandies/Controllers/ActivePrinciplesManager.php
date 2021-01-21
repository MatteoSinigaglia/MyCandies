<?php


namespace MyCandies\Controllers;

require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'dbh.php';
require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'Exceptions'.DS.'DBException.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Category.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'ActivePrinciple.php';
require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';
require_once MYCANDIES_PATH.DS.'Tables'.DS.'Table.php';

use DB\dbh;
use DB\Exceptions\DBException;
use Exception;
use MyCandies\Entities;
use MyCandies\Entities\ActivePrinciple;
use MyCandies\Tables\Table;

class ActivePrinciplesManager
{

    private $T_activePrinciples;

    private $dbh;

    public function __construct()
    {
        $this->dbh = new dbh();
        $constructorargs = [Entities\DB];
        $this->T_activePrinciples = new Table($this->dbh, 'ActivePrinciples', 'id', ActivePrinciple::class, $constructorargs);
    }

    public function insertActivePrinciple($activePrinciple): bool
    {
        try {
            $this->T_activePrinciples->insert($activePrinciple);
        } catch (Exception $e) {
            throw $e;
        }
        return true;
    }

    /**
     * @param $name
     * @return mixed
     * @throws DBException
     */
    public function searchIdByName($name)
    {
        /**
         * Il nome di una categoria è UNIQUE nel database
         */
        try {
            $this->dbh->connect();
            $activePrinciple = $this->T_activePrinciples->find(
                [
                    'column' => 'name',
                    'value' => $name
                ]);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }

        return $activePrinciple[0];
    }

    /**
     * @return mixed
     * @throws DBException
     */
    public function getActivePrinciples()
    {
        try {
            $this->dbh->connect();
            $activePrinciples = $this->T_activePrinciples->find();
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return $activePrinciples;
    }
}