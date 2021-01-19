<?php

namespace MyCandies\Entities;

require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Entity.php';
require_once MYCANDIES_PATH . DS . 'Exceptions' . DS . 'EntityException.php';

use MyCandies\Exceptions\EntityException;

class Product extends Entity
{

    public const PRODUCT = 1;

    private $category_id;
    private $name;
    private $description;
    private $price;
    private $availability;

    public function __construct(int $source, array $data = [])
    {
        try {
            parent::__construct($source, $data['id']);
            if ($source === self::PRODUCT) {
                $this->setCategory_id($data['category_id']);
                $this->setName($data['name']);
                $this->setDescription($data['description']);
                $this->setPrice($data['price']);
                $this->setAvailability($data['availability']);
            }
        } catch (EntityException $e) {
            throw $e;
        }
    }

    /**
     * @param $category_id
     * @throws EntityException
     */
    public function setCategory_id($category_id)
    {
        if (!isset($category_id)) {
            throw new EntityException('Non è stata scelta una categoria');
        }
        $this->category_id = $category_id;
    }

    /**
     * @throws EntityException
     */
    public function setName($name)
    {
        if (!isset($name)) {
            throw new EntityException('Non è stato inserito il nome');
        }
        $this->name = $name;
    }

    /**
     * @throws EntityException
     */
    public function setDescription($description)
    {
        if (!isset($description)) {
            throw new EntityException('Non è stato inserita la descrizione');
        }
        $this->description = $description;
    }

    /**
     * @throws EntityException
     */
    public function setPrice($price)
    {
        if (!is_numeric($price)) {
            throw new EntityException('Il prezzo inserito non è numerico');
        } else if ($price <= 0 || $price >= 10000) { // è numerico allora ->
            throw new EntityException('Il prezzo deve essere maggiore di 0 e minore di 10000');
        } else if (!preg_match('/^(.*)(\.[0-9]{1,2})?$/', $price))
            throw new EntityException('Il prezzo deve avere al massimo due valori decimali');
        else {
            $this->price = filter_var($price, FILTER_VALIDATE_FLOAT);
        }
    }

    /**
     * @throws EntityException
     */
    public function setAvailability($availability)
    {
        if (!is_numeric($availability)) {
            throw new EntityException('La quantità inserita non è numerica');
        } else if ($availability <= 0 && $availability >= 10000000) { // è numerico allora ->
            throw new EntityException('La quantità deve essere maggiore di 0 e minore di 10000000 grammi');
        } else if (!preg_match('/([1-9][0-9]{0,6})/', $availability))
            throw new EntityException('La quantità deve essere un valore intero');
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
            $fields[$key] = $value;
        }
        return $fields;
    }

}
