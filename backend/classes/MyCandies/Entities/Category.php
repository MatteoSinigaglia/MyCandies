<?php

    namespace MyCandies\Entities;

    require_once MYCANDIES_PATH.DS.'Entities'.DS.'Entity.php';
    require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

    use MyCandies\Exceptions\EntityException;

    class Category extends Entity {

        private $name;
        private $description;

        public function __construct(int $source, array $data=[]) {
            try {
                if($source === self::CONTROLLER) {
                    parent::__construct($source, $data['id']);
                    $this->name = $data['name'];
                    $this->description = $data['description'];
                }
            } catch(EntityException $e) {
                throw $e;
            }
        }

        public function getName() : string {
            return $this->name;
        }
    }
