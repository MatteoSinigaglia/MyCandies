<?php

    namespace MyCandies\Entities;

    require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';
    require_once MYCANDIES_PATH.DS.'Entities'.DS.'Entity.php';

    use MyCandies\Exceptions\EntityException;

    class Image extends Entity {

        private $img_path;

        private $errors;

        public function __construct(int $source, array $data=[]) {
            parent::__construct($source, ($data['id'] ?? null));
            if($source !== DB) {
                $this->errors = array();
                $this->setImg_path();
                if(count($this->errors)) {
                    throw new EntityException($this->errors, -1);
                }
            }
        }

        public function setImg_path() {
            if($_FILES['productImage']['size'] == 0) {
                $this->errors['img_path'] = 'Inserire un\'immagine';
            } else {
                $path = $_FILES['productImage']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $allowed = array("jpeg", "png", "jpg");
                if(!in_array($ext, $allowed)) {
                    $this->errors['img_path'] = 'Il formato dell\'immagine deve essere jpeg, png o jpg';
                } else if($_FILES['productImage']['size'] >= 512000) {
                    $this->errors['img_path'] = 'La dimensione massima dell\'immagine è 500kb';
                } else {
                    $this->img_path = '..'.DS.'img'.DS.'products'.DS.$_FILES['productImage']['name'];
                }
            }
        }

        public function getValues() : array {
            $fields = parent::getValues();
            foreach ($this as $key => $value) {
                if($key != 'errors')
                    $fields[$key] = $value;
            }
            return $fields;
        }

        public function getImg_path() {
            return $this->img_path;
        }

        public function getColumns() : array {
            $columns = array();
            foreach ($this as $key => $value) {
                if($key != 'errors')
                    array_push($columns, $key);
            }
            return $columns;
        }

    }
