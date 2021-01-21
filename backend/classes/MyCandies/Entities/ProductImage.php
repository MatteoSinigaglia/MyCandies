<?php

    namespace MyCandies\Entities;

    require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

    use MyCandies\Exceptions\EntityException;

    class ProductImage {

        private $product_id;
        private $img_id;

        public function __construct(int $source, array $data=[]) {
            try {
                if($source !== DB) {
                    $this->setProduct_id($data['product_id']);
                    $this->setImg_id($data['img_id']);
                }
            } catch (EntityException $e) {
                throw $e;
            }
        }

        public function setProduct_id($product_id) {
            if(!isset($product_id)) {
                throw new EntityException('Errore nell\'inserimento del del prodotto');
            }
            $this->product_id = $product_id;
        }

        public function setImg_id($img_id) {
            if(!isset($img_id)) {
                throw new EntityException('Errore nell\'inserimento del dell\'immagine');
            }
            $this->img_id = $img_id;
        }

        public function getValues() : array {
            $fields = [];
            foreach ($this as $key => $value) {
                $fields[$key] = $value;
            }
            return $fields;
        }

        public function getProduct_id() {
            return $this->product_id;
        }

        public function getImg_id() {
            return $this->img_id;
        }

        public function getColumns() : array {
            $columns = array();
            foreach ($this as $key => $value) {
                array_push($columns, $key);
            }
            return $columns;
        }
    }
