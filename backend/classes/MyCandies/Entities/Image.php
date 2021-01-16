<?php

    namespace MyCandies\Entities;

    require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

    use MyCandies\Exceptions\EntityException;

    class Image {
        private $img_path;

        public function __construct(array $data) {
            try {
                parent::__construct($data['id']);
                $this->img_path = $data['image'];
            } catch(EntityException $e) {
                throw $e;
            }
        }

        public function setImg_path($img_path) {
            $path = $_FILES['productImage']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $allowed = array("jpeg", "png", "jpg");
            if(!in_array($ext, $allowed)) {
                throw new EntityException('Il formato dell\'immagine deve essere jpeg, png o jpg');
            }
            else if($_FILES['productImage']['size'] >= 512000) {
                throw new EntityException('La dimensione massima dell\'immagine è 500kb');
            } else {
                $this->img_path = ROOT.DS.'img'.DS.'products'.DS.$_FILES['productImage']['name'];
            }
        }
    }
