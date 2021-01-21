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

    /**
     * @param $activePrinciple
     * @param $effectName
     * @param $sideEffectName
     * @return bool
     * @throws DBException
     */
    public function insertActivePrinciple($activePrinciple, $effectName, $sideEffectName): bool
    {
        try {
            $activePrincipleEffect = new ActivePrincipleEffect(Entities\ACTIVE_PRINCIPLES_MANAGER, [
                'active_principle_id'  => $activePrinciple->getId(),
                'effect_id' => $this->getEffectFromName($effectName)->getId()
            ]);
            $activePrincipleSideEffect = new ActivePrincipleSideEffect(Entities\ACTIVE_PRINCIPLES_MANAGER, [
                'active_principle_id'  => $activePrinciple->getId(),
                'side_effect_id' => $this->getSideEffectFromName($sideEffectName)->getId()
            ]);
            $this->dbh->connect();
            $this->dbh->transactionStart();
            $this->T_activePrinciples->insert($activePrinciple);
            $this->T_activePrinciplesEffects->insert($activePrincipleEffect);
            $this->T_activePrinciplesSideEffects->insert($activePrincipleSideEffect);
            $this->dbh->transactionCommit();
        } catch (DBException $e) {
            $this->dbh->transactionRollback();
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return true;
    }

    public function insertEffect($effect): bool
    {
        try {
            $this->dbh->connect();
            $this->dbh->transactionStart();
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

    public function insertSideEffect($sideEffect): bool
    {
        try {
            $this->dbh->connect();
            $this->dbh->transactionStart();
            $this->T_categories->insert($sideEffect);
            $this->dbh->transactionCommit();
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
        try{
            $this->dbh->connect();
            $effect = $this->T_effects->find([
                'column' => 'name',
                'value'  => $name
            ]);
        } catch (DBException $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
        return $effect[0];
    }

    private function getSideEffectFromName($name) {
        $sideeffect = array();
        try{
            $this->dbh->connect();
            $sideeffect = $this->T_sideEffects->find([
                'column' => 'name',
                'value'  => $name
            ]);
        } catch (DBException $e) {
            throw $e;
        } finally {
            $this->dbh->disconnect();
        }
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