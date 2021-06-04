<?php

    namespace MyCandies\Entities;

    require_once MYCANDIES_PATH.DS.'Entities'.DS.'Entity.php';
    require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

    use DB\dbh;
    use Exception;
    use MyCandies\Tables\Table;

    class Category extends Entity {

        private $name;

        public function __construct(int $source, array $data=[]) {
            try {
                parent::__construct($source, ($data['id'] ?? null));
                if($source === CATEGORIES_MANAGER) {
                    $this->setName($data['name']);
                }
            } catch(Exception $e) {
                throw $e;
            }
        }

        private function setName($name) {
            if(!isset($name) || $name == '') {
                throw new Exception('Il nome deve essere valorizzato');
            }
            else if($this->checkUniqueName($name))
                throw new Exception('Esiste già una categoria con lo stesso nome');
            $this->name = $name;
        }

        public function getName() : string {
            return $this->name;
        }

        public function getValues() : array {
            $fields =[];
            foreach ($this as $key => $value) {
                $fields[$key] = $value;
            }
            return $fields;
        }

        public function getColumns() : array {
            $columns = array();
            foreach ($this as $key => $value) {
                array_push($columns, $key);
            }
            return $columns;
        }

        private function checkUniqueName($name) : int
        {
            $dbh = new dbh();
            $T_category = new Table($dbh, 'Categories', 'id', Category::class, [DB]);
            $dbh->connect();
            $category = $T_category->find([
                'column' => 'name',
                'value' => $name
            ]);
            $dbh->disconnect();
            return isset($category[0]);
        }

    }
