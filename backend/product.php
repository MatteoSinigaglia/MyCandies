<?php

    class Product {
     
        private $category_id; //int
        private $name; // string
        private $description; // string
        private $price; // float
        private $availability; // grammi, float
        private $linked_category; // int
        
        private string $error; 

        public function __toString() {
            return $error;
        }

        /**
         * setters, per comodita' ritornato tutti 
         * l'oggetto stesso invece di essere metodi
         * void.
         */
        public function setCategory_id($_category_id) {
            $this->category_id = $_category_id;
            return $this;
        }

        public function setName($_name) {
            $this->name = $_name;
            return $this;
        }

        public function setDescription($_description) {
            $this->description = $_description;
            return $this;
        }

        public function setPrice($_price) {
            $this->price = $_price;
            return $this;
        }

        public function setAvailability($_availability) {
            $this->availability = $_availability;
            return $this;
        }   

        public function setLinked_category($_linked_category=0) {
            $this->linked_category = $_linked_category;
            return $this;
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

        public function getLinked_category() {
            return $this->linked_category;
        }
    } 
?>