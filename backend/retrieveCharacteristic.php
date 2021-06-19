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
        $success = $activePrinciplesManager->insertActivePrinciple(['name' => $name], $effects, $sideEffects);
    } catch(Exception $e) {
        $htmlPage = str_replace('<error_name />', '<strong class="formErrors">'.$e->getMessage().'</strong>', $htmlPage);
    } finally {
        if($success) $htmlPage = str_replace('<error_overall />', '<strong class="formSuccess">Inserimento completato!</strong>', $htmlPage);
        else $htmlPage = str_replace('<error_overall />', '<strong class="formErrors">Inserimento fallito!</strong>', $htmlPage);
    }
} else if(isset($_POST['submitCategory'])) {
    $_GET['insertCategory']='Inserisci+categoria';
    $htmlPage = insertCategory($htmlPage, $pattern, true);
    $name = ($_POST['categoryName'] ?? null);
    unset($_GET['insertCategory']);
    $success = false;
    try {
        $categoriesManager = new CategoriesManager();
        $success = $categoriesManager->insertCategory($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<error_name />', '<strong class="formErrors">'.$e->getMessage().'</strong>', $htmlPage);
    } finally {
        if($success) $htmlPage = str_replace('<error_overall />', '<strong class="formSuccess">Inserimento completato!</strong>', $htmlPage);
        else $htmlPage = str_replace('<error_overall />', '<strong class="formErrors">Inserimento fallito!</strong>', $htmlPage);
    }
} else if(isset($_POST['submitEffect'])) {
    $_GET['insertEffect']='Inserisci+effetto';
    $htmlPage = insertEffect($htmlPage, $pattern, true);
    $name = ($_POST['effectName'] ?? null);
    unset($_GET['insertEffect']);
    $success = false;
    try {
        $activePrinciplesManager = new ActivePrinciplesManager();
        $success = $activePrinciplesManager->insertEffect($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<error_name />', '<strong class="formErrors">'.$e->getMessage().'</strong>', $htmlPage);
    } finally {
        if($success) $htmlPage = str_replace('<error_overall />', '<strong class="formSuccess">Inserimento completato!</strong>', $htmlPage);
        else $htmlPage = str_replace('<error_overall />', '<strong class="formErrors">Inserimento fallito!</strong>', $htmlPage);
    }
} else if(isset($_POST['submitSideEffect'])) {
    $_GET['insertSideEffect']='Inserisci+effetto+collaterale';
    $htmlPage = insertSideEffect($htmlPage, $pattern, true);
    $name = ($_POST['sideEffectName'] ?? null);
    unset($_GET['insertSideEffect']);
    $success = false;
    try {
        $activePrinciplesManager = new ActivePrinciplesManager();
        $success = $activePrinciplesManager->insertSideEffect($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<error_name />', '<strong class="formErrors">'.$e->getMessage().'</strong>', $htmlPage);
    } finally {
        if($success) $htmlPage = str_replace('<error_overall />', '<strong class="formSuccess">Inserimento completato!</strong>', $htmlPage);
        else $htmlPage = str_replace('<error_overall />', '<strong class="formErrors">Inserimento fallito!</strong>', $htmlPage);
    }
}

$htmlPage = noFormErrors($htmlPage);
echo $htmlPage;
