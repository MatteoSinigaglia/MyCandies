<?php

    namespace MyCandies\Entities;

    class ProductImage {

        private $product_id;
        private $img_id;

        public function __construct(int $source, array $data=[]) {
            if($source !== DB) {
                $this->setProduct_id($data['product_id']);
                $this->setImg_id($data['img_id']);
            }
        }

        public function setProduct_id($product_id) {
            $this->product_id = $product_id;
        }

        public function setImg_id($img_id) {
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
