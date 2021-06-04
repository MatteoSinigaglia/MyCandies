<?php


namespace MyCandies\Controllers;

require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'dbh.php';
require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'Exceptions'.DS.'DBException.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'ActivePrinciple.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'ActivePrincipleSideEffect.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'ActivePrincipleEffect.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Effect.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'SideEffect.php';
require_once MYCANDIES_PATH.DS.'Exceptions'.DS.'EntityException.php';
require_once MYCANDIES_PATH.DS.'Tables'.DS.'Table.php';

use DB\dbh;
use DB\Exceptions\DBException;
use Exception;
use MyCandies\Entities;
use MyCandies\Entities\ActivePrinciple;
use MyCandies\Entities\ActivePrincipleEffect;
use MyCandies\Entities\ActivePrincipleSideEffect;
use MyCandies\Entities\Effect;
use MyCandies\Entities\SideEffect;
use MyCandies\Exceptions\EntityException;
use MyCandies\Tables\Table;

class ActivePrinciplesManager
{

    private $T_activePrinciples;
    private $T_effects;
    private $T_sideEffects;
    private $T_activePrinciplesSideEffects;
    private $T_activePrinciplesEffects;

    private $dbh;

    public function __construct()
    {
        $this->dbh = new dbh();
        $constructorargs = [Entities\DB];
        $this->T_activePrinciples = new Table($this->dbh, 'ActivePrinciples', 'id', ActivePrinciple::class, $constructorargs);
        $this->T_effects = new Table($this->dbh, 'Effects', 'id', Effect::class, $constructorargs);
        $this->T_sideEffects = new Table($this->dbh, 'SideEffects', 'id', SideEffect::class, $constructorargs);
        $this->T_activePrinciplesEffects = new Table($this->dbh, 'ActivePrinciplesEffects', 'id', ActivePrincipleEffect::class, $constructorargs);
        $this->T_activePrinciplesSideEffects = new Table($this->dbh, 'ActivePrinciplesSideEffects', 'id', ActivePrincipleSideEffect::class, $constructorargs);
    }

    public function insertActivePrinciple($activePrincipleArray, $effectNames, $sideEffectNames): bool
    {
        try {
            $activePrinciple = new ActivePrinciple(Entities\ACTIVE_PRINCIPLES_MANAGER, $activePrincipleArray);
            $this->dbh->connect();
            $this->dbh->transactionStart();
            $activePrincipleId = $this->T_activePrinciples->insert($activePrinciple);
            if($effectNames != null) {
                $activePrincipleEffects = array();
                foreach ($effectNames as $i) {
                    array_push($activePrincipleEffects, new ActivePrincipleEffect(Entities\ACTIVE_PRINCIPLES_MANAGER, [
                        'active_principle_id' => $activePrincipleId,
                        'effect_id' => $this->getEffectFromName($i)->getId()
                    ]));
                }
                foreach ($activePrincipleEffects as &$i) {
                    $this->T_activePrinciplesEffects->insert($i);
                }
            } else throw new Exception('Il principio attivo deve avere almeno un effetto');
            if($sideEffectNames != null) {
                $activePrincipleSideEffects = array();
                foreach ($sideEffectNames as $i) {
                    array_push($activePrincipleSideEffects, new ActivePrincipleSideEffect(Entities\ACTIVE_PRINCIPLES_MANAGER, [
                        'active_principle_id' => $activePrincipleId,
                        'side_effect_id' => $this->getSideEffectFromName($i)->getId()
                    ]));
                }
                foreach ($activePrincipleSideEffects as $i) {
                    $this->T_activePrinciplesSideEffects->insert($i);
                }
            } else throw new Exception('Il principio attivo deve avere almeno un effetto collaterale');
            $this->dbh->transactionCommit();
        } catch (DBException $e) {
            $this->dbh->transactionRollback();
            throw $e;
        } catch(EntityException | Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return true;
    }

    public function insertEffect($effectName): bool
    {
        try {
            $this->dbh->connect();
            $this->dbh->transactionStart();
            $effect = new Effect(Entities\ACTIVE_PRINCIPLES_MANAGER, [
                'name' => $effectName]);
            $this->T_effects->insert($effect);
            $this->dbh->transactionCommit();
        } catch (EntityException | DBException | Exception $e) {
            $this->dbh->transactionRollback();
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return true;
    }

    public function insertSideEffect($sideEffectName): bool
    {
        try {
            $this->dbh->connect();
            $this->dbh->transactionStart();
            $sideEffect = new SideEffect(Entities\ACTIVE_PRINCIPLES_MANAGER, [
                'name' => $sideEffectName]);
            $this->T_sideEffects->insert($sideEffect);
        } catch (Exception $e) {
            $this->dbh->transactionRollback();
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return true;
    }

    private function getEffectFromName($name) {
        $effect = $this->T_effects->find([
            'column' => 'name',
            'value'  => $name
        ]);
        return $effect[0];
    }

    private function getSideEffectFromName($name) {
        $sideEffect = $this->T_sideEffects->find([
            'column' => 'name',
            'value'  => $name
        ]);
        return $sideEffect[0];
    }

    public function loadEffects() : array {
        $effects = array();
        try{
            $this->dbh->connect();
            $effects = $this->T_effects->find();
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return $effects;
    }

    public function loadSideEffects() : array {
        $sideEffects = array();
        try{
            $this->dbh->connect();
            $sideEffects = $this->T_sideEffects->find();
        } catch (DBException $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return $sideEffects;
    }

    public function searchIdByName($name)
    {
        try {
            $this->dbh->connect();
            $activePrinciple = $this->T_activePrinciples->find(
                [
                    'column' => 'name',
                    'value' => $name
                ]);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return $activePrinciple[0];
    }

    public function getActivePrinciples()
    {
        try {
            $this->dbh->connect();
            $activePrinciples = $this->T_activePrinciples->find();
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return $activePrinciples;
    }
}
