<?php

    namespace MyCandies\Entities;

    require_once MYCANDIES_PATH.DS.'Entities'.DS.'Entity.php';
    require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

    use MyCandies\Exceptions\EntityException;

    class Category extends Entity {

        private $name;

        public function __construct(int $source, array $data=[]) {
            try {
                parent::__construct($source, (isset($data['id']) ? $data['id'] : null));
                if($source === CATEGORIES_MANAGER) {
                    $this->setName($data['name']);
                }
            } catch(EntityException $e) {
                throw $e;
            }
        }

        private function setName($name) {
            if(!isset($name) || $name == '')
                throw new EntityException('Il nome deve essere valorizzato');
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

    }
