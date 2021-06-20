<?php


namespace MyCandies\Entities;

use Exception;

class ActivePrincipleEffect {

    private $active_principle_id;
    private $effect_id;

    public function __construct(int $source, array $data=[])
    {
        try {
            if ($source === ACTIVE_PRINCIPLES_MANAGER) {
                $this->setActive_principle_id($data['active_principle_id']);
                $this->effect_id = $data['effect_id'];
            }
        } catch(Exception $e) {
            throw  $e;
        }
    }

    private function setActive_principle_id($active_principle_id) {
        if(!isset($active_principle_id) || $active_principle_id == '')
            throw new Exception('Non esiste un principio attivo senza effetti');
        $this->active_principle_id = $active_principle_id;
    }
    public function getActive_principle_id() : int {
        return $this->active_principle_id;
    }

    public function getEffect_id() : int {
        return $this->effect_id;
    }

    public function getValues() : array {
        $fields = [];
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
