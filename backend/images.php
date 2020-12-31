<?php
    class Images {
        private $error ="";
        private $connection;

        public function __construct($connection) {
            $this->connection = $connection;
        }

        public function __toString() {
            return $this->error;
        }

        public function validateUpload() {
            $tmpPath = $_FILES['productImage']['name'];
            
            $ext = pathinfo($tmpPath, PATHINFO_EXTENSION);
            echo $tmpPath   ;
            $allowed = array("jpeg", "png", "jpg");
            if(!in_array($ext, $allowed)) {
                $this->error .= "Il formato dell'immagine deve essere jpeg, png, jpg";
                return false;
            }
            else if($_FILES['productImage']['size'] >= 512000) {
                $this->error .= "La dimensione massima dell'immagine è 500kb";
                return false;
            }
            $this->upload();
            return true;
        }

        private function upload() {
            $uploaddir = '../img/products/';
            $uploadfile = $uploaddir . $_FILES['productImage']['name'];
            if(!move_uploaded_file( $_FILES['productImage']['tmp_name'], $uploadfile)) {
                $this->error .= "Errore nell'upload dell'immagine";
            }
        }

        private function uploadOnDb() {}
    }
?>