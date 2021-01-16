<?php
namespace MyCandies\Controllers;

use DB\dbh;
use MyCandies\Tables\Table;
use MyCandies\Entity\Category;

class InsertProduct {

    private $T_categories;
    private $category;
    private $dbh;
    
    private const PATH_TO_ENTITY = '.'.DS.'..'.DS.'Entity'.DS;

    public function __construct(array $category = null) {
        try{
            $this->dbh = new dbh();
            $this->category = (isEmpty($category) ? null : $category);
            $this->T_categories = new Table($this->dbh, 'Categories', 'id', self::PATH_TO_ENTITY.'Category');
        } catch (EntityException $e) {
            throw $e;
        }
    }

    /**
     * TODO
     */
    public function insertCategory() : bool {
		return true;
    }

    /**
     * @return: array associativo di tutte le categorie
     */
    public function getCategories() {
        try{
            $this->dbh->connect();
            $categories = $this->T_categories->find(['where' => '1']);
        } catch (Exception $e ) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }

        return $categories;
    }
}
?>