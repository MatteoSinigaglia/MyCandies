<?php


namespace MyCandies\Entities;

use MyCandies\Exceptions\EntityException;

class Effect extends Entity {

    private $name;

    public function __toString() : string {
        return $this->name;
    }

    public function __construct(int $source, array $data=[]) {
        try {
            parent::__construct($source, (isset($data['id']) ? $data['id'] : null));
            if($source !== DB) {
                $this->setName($data['name']);
            }
        } catch(EntityException $e) {
            throw $e;
        }
    }

    private function setName($name) {
        if(!isset($name) || $name == '')
            throw new EntityException('Il nome deve essere valorizzato');
        $this->name = $name;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getValues() : array {
        $fields = [];
        parent::getValues();
        foreach ($this as $key => $value) {
            $fields[$key] = $value;
        }
        return $fields;
    }

    public function getColumns() : array {
        $columns = array();
        foreach ($this as $key => $value) {
            array_push($columns, $key);
        }
        return $columns;
    }

}