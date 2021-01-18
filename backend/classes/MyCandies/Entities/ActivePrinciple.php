<?php

namespace MyCandies\Entities;

require_once MYCANDIES_PATH.DS.'Entities'.DS.'Entity.php';
require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

use MyCandies\Exceptions\EntityException;

class ActivePrinciple extends Entity {

    public const ACTIVE_PRINCIPLE = 1;
    private $name;

    public function __construct(int $source, array $data=[]) {
        try {
            if($source === self::ACTIVE_PRINCIPLE) {
                parent::__construct($source, $data['id']);
                $this->name = $data['name'];
            }
        } catch(EntityException $e) {
            throw $e;
        }
    }

    public function getName() : string {
        return $this->name;
    }

    public function getValues() : array {
        $fields = parent::getValues();
        foreach ($this as $key => $value) {
            $fields[$key] = $value;
        }
        return $fields;
    }

    public function getValuesWithoutId() : array {
        $fields=[];
        foreach ($this as $key => $value) {
            $fields[$key] = $value;
        }
        return $fields;
    }
}
