<?php

namespace MyCandies\Controllers;

require_once MYCANDIES_PATH.DS.'Tables'.DS.'Table.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Category.php';
require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Entity.php';

use Exception;
use DB\dbh;
use MyCandies\Tables\Table;
use MyCandies\Entities\Category;
use MyCandies\Entities\Entity;
use MyCandies\Exceptions\EntityException;

class ManageCategories {

    private $T_categories;

    private $dbh;

    public function __construct(array $category = null) {
        try{
            $this->dbh = new dbh();
            $this->T_categories = new Table($this->dbh, 'Categories', 'id', Category::class);
        } catch (EntityException $e) {
            throw $e;
        }
    }

    /**
     * TODO
     */
    public function insertCategory($category) : bool {
        $this->category = (empty($category) ? null : new Category(Entity::CONTROLLER, $category));
		return true;
    }

    /**
     * @return: array associativo di tutte le categorie
     */
    public function getCategories() {
        try{
            $this->dbh->connect();
            $categories = $this->T_categories->find();
        } catch (Exception $e ) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }

        return $categories;
    }
}
