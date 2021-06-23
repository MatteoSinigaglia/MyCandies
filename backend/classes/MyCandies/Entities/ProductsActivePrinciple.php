<?php

namespace MyCandies\Entities;

require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

use MyCandies\Exceptions\EntityException;

class ProductsActivePrinciple {

    private $product_id;
    private $active_principle_id;
    private $percentage;

    private $errors;

    public function __construct(int $source, array $data=[]) {
        if($source !== DB) {
            $this->errors = array();
            $this->product_id = $data['product_id'];
            $this->setActive_principle_id($data['active_principle_id']);
            $this->setPercentage($data['percentage']);
            if(count($this->errors)) {
                throw new EntityException($this->errors, -1);
            }
        }
    }

    private function setPercentage($percentage) {
        if($percentage == null) {
            $this->errors['percentage'] = 'Non è stata scelta la percentuale di principio attivo';
        } else if(!preg_match('/^([1-9][0-9]{0,3})$/',$percentage)) {
            $this->errors['percentage'] = 'La percentuale di principio attivo deve essere un valore intero';
        } else if($percentage > 100 || $percentage < 0) {
            $this->errors['percentage'] = 'La percentuale di principio attivo deve essere compresa tra 0 e 100';
        } else $this->percentage = filter_var($percentage, FILTER_VALIDATE_INT);
    }

    private function setActive_principle_id($active_principle_id) {
        $this->active_principle_id = $active_principle_id;
    }

    public function getProduct_id() : int {
        return $this->product_id;
    }

    public function getActive_principle_id() : int {
        return $this->active_principle_id;
    }

    public function getPercentage() : int {
        return $this->percentage;
    }

    public function getValues() : array {
        $fields = [];
        foreach ($this as $key => $value) {
            if($key != 'errors')
                $fields[$key] = $value;
        }
        return $fields;
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
