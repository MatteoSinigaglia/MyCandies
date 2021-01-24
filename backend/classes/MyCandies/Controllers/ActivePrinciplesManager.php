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

    /**
     * ActivePrinciplesManager constructor.
     */
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

    /**
     * @param $activePrincipleArray
     * @param $effectNames
     * @param $sideEffectNames
     * @return bool
     * @throws DBException
     * @throws EntityException
     */
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
            }
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
            }
            $this->dbh->transactionCommit();
        } catch (DBException $e) {
            $this->dbh->transactionRollback();
            throw $e;
        } catch(EntityException $e) {
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
        } catch (DBException $e) {
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
        } catch (DBException $e) {
            $this->dbh->transactionRollback();
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return true;
    }

    private function getEffectFromName($name) {
        $effect = array();
        $effect = $this->T_effects->find([
            'column' => 'name',
            'value'  => $name
        ]);
        return $effect[0];
    }

    private function getSideEffectFromName($name) {
        $sideeffect = array();
        $sideeffect = $this->T_sideEffects->find([
            'column' => 'name',
            'value'  => $name
        ]);
        return $sideeffect[0];
    }

    public function loadEffects() : array {
        $effects = array();
        try{
            $this->dbh->connect();
            $effects = $this->T_effects->find();
        } catch (DBException $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return $effects;
    }

    public function loadSideEffects() : array {
        $sideeffects = array();
        try{
            $this->dbh->connect();
            $sideeffects = $this->T_sideEffects->find();
        } catch (DBException $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return $sideeffects;
    }

    /**
     * @param $name
     * @return mixed
     * @throws DBException
     */
    public function searchIdByName($name)
    {
        /**
         * Il nome di una categoria è UNIQUE nel database
         */
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

    /**
     * @return mixed
     * @throws DBException
     */
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