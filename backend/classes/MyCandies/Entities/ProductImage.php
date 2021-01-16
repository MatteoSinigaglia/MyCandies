<?php

    namespace MyCandies\Entity;

    class ProductImage extends Entity {

        private $product_id;
        private $image_id;

        public function __construct(array $data) {
            // parent::__construct(null);
            $this->product_id = $data['product_id'];
            $this->$image_id = $data['image_id'];
        }
    }
?>