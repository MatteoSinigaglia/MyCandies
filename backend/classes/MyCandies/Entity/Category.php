<?php

    use MyCandies\Table;
    namespace MyCandies\Entity;

    class Category extends Entity {

        private $name;
        private $description;

        public function __construct(array $data) {
            parent::__construct($data['id']);
            $this->name = $data['name'];
            $this->description = $data['description'];
        }
    }

?>