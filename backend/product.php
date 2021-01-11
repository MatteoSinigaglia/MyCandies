<?php
    require_once __DIR__ . DIRECTORY_SEPARATOR . "categories.php";

    class Product {
     
        private $category_id; //int
        private $name; // string
        private $description; // string
        private $price; // float
        private $availability; // grammi, float
        private $linked_category; // int
        
        private $error; 

        public function __construct() {
            $this->error = "";
        }

        public function __toString() {
            return $this->error;
        }

        /**
         * setters, per comodita' ritornato tutti 
         * l'oggetto stesso invece di essere metodi
         * void.
         */
        public function setCategory_id($category_id) {
            $this->category_id = $category_id;
            return $this;
        }

        public function setName($name) {
            $this->name = $name;
            return $this;
        }

        public function setDescription($description) {
            $this->description = $description;
            return $this;
        }

        public function setPrice($price) {
            if(!preg_match('/^([1-9][0-9]{1,4}|0)(\.[0-9]{1,2})?$/',$price)) {
                $this->error .= "<li class=\"failure\">Il prezzo deve contenere cifre, al massimo 5 cifre intere e i decimali sono separati dal carattere .</li>";
                $this->error .= "Valore del prezzo troppo alto";
            }
            $this->price = filter_var($price, FILTER_VALIDATE_FLOAT);
            return $this;
        }

        public function setAvailability($availability) {
            if(!preg_match('/^([1-9][0-9]{1,7}|0)$/',$availability))
                $this->error .= "<li class=\"failure\">La quantità di prodotto deve essere un valore numerico intero di al massimo 7 cifre</li>";
            $this->availability = filter_var($availability, FILTER_VALIDATE_INT);
            return $this;
        }   

        public function setLinked_category($linked_category=1) {
            $this->linked_category = $linked_category;
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