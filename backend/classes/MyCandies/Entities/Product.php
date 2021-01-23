<?php

namespace MyCandies\Entities;

require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Entity.php';
require_once MYCANDIES_PATH . DS . 'Exceptions' . DS . 'EntityException.php';

use MyCandies\Exceptions\EntityException;

class Product extends Entity
{
    private $category_id;
    private $name;
    private $description;
    private $price;
    private $availability;

    private $errors;

    public function __construct(int $source, array $data = [])
    {
            parent::__construct($source, (isset($data['id']) ? $data['id'] : null));
            if ($source === PRODUCTS_MANAGER) {
                $this->errors = array();
                $this->setCategory_id($data['category_id']);
                $this->setName($data['name']);
                $this->setDescription($data['description']);
                $this->setPrice($data['price']);
                $this->setAvailability($data['availability']);
            }
            if(count($this->errors)) {
                throw new EntityException($this->errors, -1);
            }
    }

    public function setCategory_id($category_id)
    {
        if (!isset($category_id) || $category_id == '') {
            $this->errors['category'] = 'Non è stata scelta una categoria';
        } else {
            $this->category_id = $category_id;
        }
    }

    public function setName($name)
    {
        if ($name == '' || $name == 'Nome prodotto') {
            $this->errors['name'] = 'Non è stato inserito il nome';
        } else if(!preg_match('/^\w+(\s\w+)*$/', $name)) {
            $this->errors['name'] = 'Il nome deve contenere caratteri alfanumerici';
        } else {
            $this->name = $name;
        }
    }

    public function setDescription($description)
    {
        if ($description == '') {
            $this->errors['description'] = 'Non è stato inserita la descrizione';
        } else {
            $this->description = $description;
        }
    }

    public function setPrice($price)
    {
        if (!is_numeric($price)) {
            $this->errors['price'] = 'Il prezzo inserito non è numerico';
        } else if ($price <= 0 || $price >= 10000) { // è numerico allora ->
            $this->errors['price'] = 'Il prezzo deve essere maggiore di 0 e minore di 10000';
        } else if (!preg_match('/^\d+(,\d{1,2})?\b$/', $price))
            $this->errors['price'] = 'Il prezzo deve avere al massimo due valori decimali, separati da una virgola';
        else {
            $this->price = filter_var($price, FILTER_VALIDATE_FLOAT);
        }
    }

    public function setAvailability($availability)
    {
        if (!is_numeric($availability)) {
            $this->errors['availability'] = 'La quantità inserita non è numerica';
        } else if ($availability <= 0 && $availability >= 10000000) { // è numerico allora ->
            $this->errors['availability'] = 'La quantità deve essere maggiore di 0 e minore di 10000000';
        } else if (!preg_match('/([1-9][0-9]{0,6})/', $availability))
            $this->errors['availability'] = 'La quantità deve essere un valore intero';
        else {
            $this->availability = filter_var($availability, FILTER_VALIDATE_INT);
        }
    }

    /**
     * getters
     */
    public function getCategory_id()
    {
        return $this->category_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    public function getValues(): array
    {
        $fields = parent::getValues();
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
