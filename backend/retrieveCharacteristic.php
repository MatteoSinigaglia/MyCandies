<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ActivePrinciplesManager.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'CategoriesManager.php';
require_once LIB_PATH . DS . 'insertCharacteristicsLib.php';
require_once LIB_PATH . DS . 'functions.php';

use MyCandies\Controllers\ActivePrinciplesManager;
use MyCandies\Controllers\CategoriesManager;

[
    'DOM' => $htmlPage,
    'pattern' => $pattern
] = initInsertCharacteristics();

if(isset($_POST['submitActivePrinciple'])) {
    $_GET['insertActivePrinciple']='Inserisci+principio+attivo';
    $htmlPage = insertActivePrinciple($htmlPage, $pattern, true);
    $name = ($_POST['activePrincipleName'] ?? null);
    $effects = ($_POST['effects'] ?? null);
    $sideEffects = ($_POST['sideEffects'] ?? null);
    unset($_GET['insertActivePrinciple']);
    $success = false;
    try {
        $activePrinciplesManager = new ActivePrinciplesManager();
        if($name != null) {
            $success = $activePrinciplesManager->insertActivePrinciple([
                'name' => $name], $effects, $sideEffects);
        }
    } catch(Exception $e) {
        $htmlPage = str_replace('<error_name />', '<p class="formErrors">'.$e->getMessage().'</p>', $htmlPage);
    } finally {
        if($name == null)
            $htmlPage = str_replace('<error_name />', '<p class="formErrors">Inserire un nome</p>', $htmlPage);
        else if($success) $htmlPage = str_replace('<error_overall />', '<p class="formSuccess">Valore inserito</p>', $htmlPage);
        else $htmlPage = str_replace('<error_overall />', '<p class="formErrors">Inserimento fallito</p>', $htmlPage);
    }
} else if(isset($_POST['submitCategory'])) {
    $_GET['insertCategory']='Inserisci+categoria';
    $htmlPage = insertCategory($htmlPage, $pattern);
    $name = ($_POST['categoryName'] ?? null);
    unset($_GET['insertCategory']);
    $success = false;
    try {
        $categoriesManager = new CategoriesManager();
        if($name != null)
            $success = $categoriesManager->insertCategory($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<error_name />', '<p class="formErrors">'.$e->getMessage().'</p>', $htmlPage);
    } finally {
        if($name == null)
            $htmlPage = str_replace('<error_name />', '<p class="formErrors">Inserire un nome</p>', $htmlPage);
        else if($success) $htmlPage = str_replace('<error_overall />', '<p class="formSuccess">Valore inserito</p>', $htmlPage);
        else $htmlPage = str_replace('<error_overall />', '<p class="formErrors">Inserimento fallito</p>', $htmlPage);
    }
} else if(isset($_POST['submitEffect'])) {
    $_GET['insertEffect']='Inserisci+effetto';
    $htmlPage = insertEffect($htmlPage, $pattern, true);
    $name = ($_POST['effectName'] ?? null);
    unset($_GET['insertEffect']);
    $success = false;
    try {
        $activePrinciplesManager = new ActivePrinciplesManager();
        if($name != null)
            $success = $activePrinciplesManager->insertEffect($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<error_name />', '<p class="formErrors">'.$e->getMessage().'</p>', $htmlPage);
    } finally {
        if($name == null)
            $htmlPage = str_replace('<error_name />', '<p class="formErrors">Inserire un nome</p>', $htmlPage);
        else if($success) $htmlPage = str_replace('<error_overall />', '<p class="formSuccess">Valore inserito</p>', $htmlPage);
        else $htmlPage = str_replace('<error_overall />', '<p class="formErrors">Inserimento fallito</p>', $htmlPage);
    }
} else if(isset($_POST['submitSideEffect'])) {
    $_GET['insertSideEffect']='Inserisci+effetto+collaterale';
    $htmlPage = insertSideEffect($htmlPage, $pattern, true);
    $name = ($_POST['sideEffectName'] ?? null);
    unset($_GET['insertSideEffect']);
    $success = false;
    try {
        $activePrinciplesManager = new ActivePrinciplesManager();
        if($name != null)
            $success = $activePrinciplesManager->insertSideEffect($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<error_name />', '<p class="formErrors">'.$e->getMessage().'</p>', $htmlPage);
    } finally {
        if($name == null)
            $htmlPage = str_replace('<error_name />', '<p class="formErrors">Inserire un nome</p>', $htmlPage);
        else if($success) $htmlPage = str_replace('<error_overall />', '<p class="formSuccess">Valore inserito</p>', $htmlPage);
        else $htmlPage = str_replace('<error_overall />', '<p class="formErrors">Inserimento fallito</p>', $htmlPage);
    }
}

$htmlPage = noFormErrors($htmlPage);
echo $htmlPage;
