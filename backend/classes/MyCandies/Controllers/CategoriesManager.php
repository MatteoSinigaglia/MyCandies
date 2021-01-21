<?php

namespace MyCandies\Controllers;

require_once MYCANDIES_PATH . DS . 'Tables' . DS . 'Table.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Category.php';
require_once MYCANDIES_PATH . DS . 'Exceptions' . DS . 'EntityException.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Entity.php';

use DB\dbh;
use DB\Exceptions\DBException;
use Exception;
use MyCandies\Entities\Category;
use MyCandies\Entities;
use MyCandies\Tables\Table;

class CategoriesManager
{

    private $T_categories;

    private $dbh;

    public function __construct()
    {
        $this->dbh = new dbh();
        $constructorargs = [Entities\DB];
        $this->T_categories = new Table($this->dbh, 'Categories', 'id', Category::class, $constructorargs);
    }

    /**
     * @param $category
     * @return bool
     * @throws Exception
     */
    public function insertCategory($category): bool
    {
        try {
            $this->dbh->connect();
            $this->dbh->transactionStart();
            $this->T_categories->insert($category);
            $this->dbh->transactionCommit();
        } catch (Exception $e) {
            $this->dbh->transactionRollback();
            throw $e;
        } finally {
            $this->dbh->disconnect();
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
            $categories = $this->T_categories->find(
                [
                    'column' => 'name',
                    'value' => $name
                ]);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }

        return $categories[0];
    }

    /**
     * @return mixed
     * @throws DBException
     */
    public function getCategories()
    {
        try {
            $this->dbh->connect();
            $categories = $this->T_categories->find();
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return $categories;
    }
}
