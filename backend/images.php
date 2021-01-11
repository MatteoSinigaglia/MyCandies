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
            $name = $_FILES['productImage']['name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $allowed = array("jpeg", "png", "jpg");
            if(!in_array($ext, $allowed)) {
                $this->error .= "<li class=\"failure\">Il formato dell'immagine deve essere jpeg, png, jpg</li>";
                return false;
            }
            else if($_FILES['productImage']['size'] >= 512000) {
                $this->error .= "<li class=\"failure\">La dimensione massima dell'immagine è 500kb</li>";
                return false;
            }
            if($this->uploadOnDb()) {
                $this->upload();
            } else {
                $this->error .= "<li class=\"failure\">Errore nell'inserimento dell'immagine: riprova fra qualche minuto.</li>";
                return false;
            }
            return true;
        }

        private function upload() {
            $uploaddir = '../img/products/';
            $uploadfile = $uploaddir . $_FILES['productImage']['name'];
            if(!move_uploaded_file( $_FILES['productImage']['tmp_name'], $uploadfile)) {
                $this->error .= "<li class=\"failure\">Errore nell'upload dell'immagine</li>";
            }
        }

        private function uploadOnDb() {
            $uploadfile = '../img/products/' . $_FILES['productImage']['name'];
            $insertQuery = "
            insert into Images(
                img_path
            )
            values(
                \"{$uploadfile}\"
            )"
            ;
            return  mysqli_query($this->connection, $insertQuery);
        }
    }
?>