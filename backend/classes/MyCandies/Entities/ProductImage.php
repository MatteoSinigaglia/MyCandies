<?php

    namespace MyCandies\Entities;


    use MyCandies\Exceptions\EntityException;

    class ProductImage extends Entity {

        private $product_id;
        private $image_id;

        public function __construct(array $data) {
            try {
                parent::__construct(null);
            } catch (EntityException $e) {
                throw $e;
            }
            $this->product_id = $data['product_id'];
            $this->image_id = $data['image_id'];
        }
    }
