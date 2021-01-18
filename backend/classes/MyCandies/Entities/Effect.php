<?php


namespace MyCandies\Entities;

use MyCandies\Exceptions\EntityException;

class Effect extends Entity {
    public const EFFECT = 1;
    private $name;
    private $description;

    public function __construct(int $source, array $data=[]) {
        try {
            if($source === self::EFFECT) {
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
        $fields = [];
        foreach ($this as $key => $value) {
            $fields[$key] = $value;
        }
        return $fields;
    }
}