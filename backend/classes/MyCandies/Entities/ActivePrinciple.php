<?php

namespace MyCandies\Entities;

require_once MYCANDIES_PATH.DS.'Entities'.DS.'Entity.php';
require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';

use DB\dbh;
use MyCandies\Entities;
use Exception;
use MyCandies\Tables\Table;

class ActivePrinciple extends Entity {

    private $name;

    public function __construct(int $source, array $data=[]) {
        try {
            parent::__construct($source, ($data['id'] ?? null));
            if($source === Entities\ACTIVE_PRINCIPLES_MANAGER) {
                $this->setName($data['name']);
            }
        } catch(Exception $e) {
            throw $e;
        }
    }

    private function setName($name) {
        if(!isset($name) || $name == '') {
            throw new Exception('Il nome deve avere un valore');
        } else if(!(preg_match('/^\w+(\s\w+)*$/', $name) && preg_match('/.*[aA-zZ].*/', $name))) {
            throw new Exception('Il nome deve contenere caratteri alfanumerici');
        }
        else if($this->checkUniqueName($name))
            throw new Exception('Esiste già un principio attivo con lo stesso nome');
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
        $T_activePrinciples = new Table($dbh, 'ActivePrinciples', 'id', ActivePrinciple::class, [DB]);
        $dbh->connect();
        $activePrinciple = $T_activePrinciples->find([
            'column' => 'name',
            'value' => $name
        ]);
        $dbh->disconnect();
        return isset($activePrinciple[0]);
    }
}
