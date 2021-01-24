<?php


namespace MyCandies\Entities;

use DB\dbh;
use Exception;
use MyCandies\Tables\Table;

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
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @throws Exception
     */
    private function setName($name) {
        if(!isset($name) || $name == '')
            throw new Exception('Il nome deve essere valorizzato');
        else if($this->checkUniqueName($name))
            throw new Exception('Esiste già un effetto con lo stesso nome');
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

    private function checkUniqueName($name) : int
    {
        $dbh = new dbh();
        $T_effects = new Table($dbh, 'Effects', 'id', Effect::class, [DB]);
        $dbh->connect();
        $effect = $T_effects->find([
            'column' => 'name',
            'value' => $name
        ]);
        $dbh->disconnect();
        return isset($effect[0]);
    }
}