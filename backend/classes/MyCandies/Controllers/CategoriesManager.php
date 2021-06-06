<?php

namespace MyCandies\Controllers;

require_once MYCANDIES_PATH . DS . 'Tables' . DS . 'Table.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Category.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Entity.php';

use DB\dbh;
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

    public function insertCategory($name): bool
    {
        try {
            $this->dbh->connect();
            $this->dbh->transactionStart();
            $category = new Category(Entities\CATEGORIES_MANAGER, [
                'name' => $name]);
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

    public function searchIdByName($name)
    {
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
