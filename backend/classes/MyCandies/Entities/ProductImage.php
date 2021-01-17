<?php

    namespace MyCandies\Entities;

    require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

    use MyCandies\Exceptions\EntityException;

    class ProductImage extends Entity {

        public const PRODUCT_IMAGES = 1;

        private $product_id;
        private $image_id;

        public function __construct(int $source, array $data=[]) {
            try {
                parent::__construct($source);
                if($source === self::PRODUCT_IMAGES) {
                    $this->setProduct_id($data['product_id']);
                    $this->setImage_id($data['image_id']);
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

        public function setImage_id($image_id) {
            if(!isset($image_id)) {
                throw new EntityException('Errore nell\'inserimento del dell\'immagine');
            }
            $this->image_id = $image_id;
        }
    }
