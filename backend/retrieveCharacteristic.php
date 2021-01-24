<?php

//TODO rimuovere include a paths
require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ActivePrinciplesManager.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'CategoriesManager.php';

use MyCandies\Controllers\ActivePrinciplesManager;
use MyCandies\Controllers\CategoriesManager;

if(isset($_POST['submitActivePrinciple'])) {
    ob_start();
    $_GET['insertActivePrinciple']='Inserisci+principio+attivo';
    include 'insertCharacteristics.php';
    $htmlPage = ob_get_clean();
    $name = $_POST['activePrincipleName'];
    $effects = $_POST['effects'];
    $sideEffects = $_POST['sideEffects'];
    unset($_GET['insertActivePrinciple']);
    try {
        $activePrinciplesManager = new ActivePrinciplesManager();
        $activePrinciplesManager->insertActivePrinciple([
            'name' => $name], $effects, $sideEffects);
    } catch(Exception $e) {
        $htmlPage = str_replace('<errName />', '<p>'.$e->getMessage().'</p>', $htmlPage);
    } finally {
        echo $htmlPage;
    }
} else if(isset($_POST['submitCategory'])) {
    ob_start();
    $_GET['insertCategory']='Inserisci+categoria';
    include 'insertCharacteristics.php';
    $htmlPage = ob_get_clean();
    $name = $_POST['categoryName'];
    unset($_GET['insertCategory']);
    try {
        $categoriesManager = new CategoriesManager();
        $categoriesManager->insertCategory($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<errName />', '<p>'.$e->getMessage().'</p>', $htmlPage);
    } finally {
        echo $htmlPage;
    }
} else if(isset($_POST['submitEffect'])) {
    ob_start();
    $_GET['insertEffect']='Inserisci+effetto';
    include 'insertCharacteristics.php';
    $htmlPage = ob_get_clean();
    $name = $_POST['effectName'];
    unset($_GET['insertEffect']);
    try {
        $activePrinciplesManager = new ActivePrinciplesManager();
        $activePrinciplesManager->insertEffect($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<errName />', '<p>'.$e->getMessage().'</p>', $htmlPage);
    } finally {
        echo $htmlPage;
        die();
    }
} else if(isset($_POST['submitSideEffect'])) {
    ob_start();
    $_GET['insertSideEffect']='Inserisci+effetto+collaterale';
    include 'insertCharacteristics.php';
    $htmlPage = ob_get_clean();
    $name = $_POST['sideEffectName'];
    unset($_GET['insertSideEffect']);
    try {
        $activePrinciplesManager = new ActivePrinciplesManager();
        $activePrinciplesManager->insertSideEffect($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<errName />', '<p>'.$e->getMessage().'</p>', $htmlPage);
    } finally {
        echo $htmlPage;
        die();
    }
}

