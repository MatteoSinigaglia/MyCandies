<?php

namespace MyCandies\Entities;

require_once MYCANDIES_PATH.DS.'Entities'.DS.'Entity.php';
require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

use MyCandies\Exceptions\EntityException;

class SideEffect extends Entity {

    public const SIDE_EFFECT = 1;
    private $name;
    private $description;

    public function __construct(int $source, array $data=[]) {
        try {
            if($source === self::SIDE_EFFECT) {
                parent::__construct($source, $data['id']);
                $this->name = $data['name'];
                $this->description = $data['description'];
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

}