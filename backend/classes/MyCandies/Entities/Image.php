<?php

    namespace MyCandies\Entities;

    require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';
    require_once MYCANDIES_PATH.DS.'Entities'.DS.'Entity.php';

    use MyCandies\Exceptions\EntityException;

    class Image extends Entity {

        public const IMAGE = 1;

        private $img_path;

        public function __construct(int $source, array $data=[]) {
            try {
                parent::__construct($source, $data['id']);
                if($source === self::IMAGE) {
                    $this->setImg_path();
                }
            } catch(EntityException $e) {
                throw $e;
            }
        }

        /**
         * @throws EntityException
         */
        public function setImg_path() {
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

        public function getValues() : array {
            $fields = parent::getValues();
            foreach ($this as $key => $value) {
                $fields[$key] = $value;
            }
            return $fields;
        }

        public function getImg_path() {
            return $this->img_path;
        }

    }
