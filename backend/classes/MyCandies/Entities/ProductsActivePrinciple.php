<?php

namespace MyCandies\Entities;

require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

use MyCandies\Exceptions\EntityException;

class ProductsActivePrinciple {

    public const PRODUCTS_ACTIVE_PRINCIPLE = 1;

    private $product_id;
    private $active_principle_id;
    private $percentage;

    public function __construct(int $source, array $data=[]) {
        try {
            if($source === self::PRODUCTS_ACTIVE_PRINCIPLE) {
                $this->product_id = $data['product_id'];
                $this->active_principle_id = $data['active_principle_id'];
                $this->setPercentage($data['percentage']);
            }
        } catch(EntityException $e) {
            throw $e;
        }
    }

    private function setPercentage($percentage) {
        if(!isset($percentage)) {
            throw new EntityException('Non è stata scelta la percentuale di principio attivo');
        } else if(!preg_match('/([1-9][0-9]{0,3})/',$percentage)) {
            throw new EntityException('La percentuale di principio attivo deve essere un valore intero');
        } else if($percentage > 100 || $percentage < 0) {
            throw new EntityException('La percentuale di principio attivo deve essere compresa tra 0 e 100');
        }
        $this->percentage = filter_var($percentage, FILTER_VALIDATE_INT);
    }

    public function getValues() : array {
        $fields = [];
        foreach ($this as $key => $value) {
            $fields[$key] = $value;
        }
        return $fields;
    }

}