<?php

    namespace MyCandies\Entity;

    use MyCandies\Exceptions\EntityException;

    class Product {
     
        private $category_id;
        private $name;
        private $description;
        private $price;
        private $availability;

        public function __construct($category_id, $name, $description, $price, $availability) {
            try {
                $this->setCategory_id($category_id);
                $this->setName($name);
                $this->setDescription($description);
                $this->setPrice($price);
                $this->setAvailability($availability);
            } catch (EntityException $e) {
                throw $e;
            }
        }

        /**
         * setters, per comodita' ritornato tutti 
         * l'oggetto stesso invece di essere metodi
         * void.
         */
        public function setCategory_id($category_id) {
            $this->category_id = $category_id;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function setPrice($price) {
            if(!is_numeric($price)) {
                throw new EntityException('Il prezzo inserito non è numerico');
            } else if($price > 0 && $price < 10000) { // è numerico allora ->
                throw new EntityException('Il prezzo deve essere maggiore di 0 e minore di 10000');
            } else if(!preg_match('/^(.*)(\.[0-9]{1,2})?$/',$price))
            throw new EntityException('Il prezzo deve avere al massimo due valori decimali');
            else {
                $this->price = filter_var($price, FILTER_VALIDATE_FLOAT);
            }
        }

        public function setAvailability($availability) {
            if(!is_numeric($price)) {
                throw new EntityException('La quantità inserita non è numerico');
            } else if($price > 0 && $price < 10000000) { // è numerico allora ->
                throw new EntityException('La quantità deve essere maggiore di 0 e minore di 10000000 grammi');
            } else if(!is_int($price))
            throw new EntityException('La quantità deve essere un valore intero');
            else {
                $this->availability = filter_var($availability, FILTER_VALIDATE_INT);
            }
        }   

        /**
         * getters
         */
        
        public function getCategory_id() {
            return $this->category_id; 
        }

        public function getName() {
            return $this->name;
        }

        public function getDescription() {
            return $this->description;
        }

        public function getPrice() {
            return $this->price;
        }

        public function getAvailability() {
            return $this->availability;
        }   

    } 
?>