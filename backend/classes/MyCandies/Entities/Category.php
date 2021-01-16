<?php

    namespace MyCandies\Entities;

    require_once MYCANDIES_PATH.DS.'Tables'.DS.'Table.php';
    require_once MYCANDIES_PATH.DS.'Entities'.DS.'Entity.php';
    require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

    use MyCandies\Tables\Table;
    use MyCandies\Entities\Entity;
    use MyCandies\Exceptions\EntityException;

    class Category extends Entity {

        private $name;
        private $description;

        public function __construct(array $data=[]) {
            try {
                parent::__construct($data['id']);
            } catch(EntityException $e) {
                throw $e;
            }
            $this->name = $data['name'];
            $this->description = $data['description'];
        }
    }
