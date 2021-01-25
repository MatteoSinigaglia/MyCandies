<?php

namespace MyCandies\Entities;

require_once MYCANDIES_PATH.DS.'Entities'.DS.'Entity.php';
require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

use DB\dbh;
use Exception;
use MyCandies\Entities;
use MyCandies\Tables\Table;

class SideEffect extends Entity {

    private $name;

    public function __toString() : string {
        return $this->name;
    }

    public function __construct(int $source, array $data=[]) {
        try {
            parent::__construct($source, (isset($data['id']) ? $data['id'] : null));
            if($source !== Entities\DB) {
                $this->setName($data['name']);
            }
        } catch(Exception $e) {
            throw $e;
        }
    }

    private function setName($name) {
        if(!isset($name) || $name == '')
            throw new Exception('Il nome deve essere valorizzato');
        else if($this->checkUniqueName($name))
            throw new Exception('Esiste già un effetto collaterale con lo stesso nome');
        $this->name = $name;
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

    public function getColumns() : array {
        $columns = array();
        foreach ($this as $key => $value) {
            array_push($columns, $key);
        }
        return $columns;
    }

    private function checkUniqueName($name) : int
    {
        $dbh = new dbh();
        $T_sideEffects = new Table($dbh, 'SideEffects', 'id', SideEffect::class, [DB]);
        $dbh->connect();
        $sideeffect = $T_sideEffects->find([
            'column' => 'name',
            'value' => $name
        ]);
        $dbh->disconnect();
        return isset($sideeffect[0]);
    }
}