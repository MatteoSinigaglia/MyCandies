<?php

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
    $name = (isset($_POST['activePrincipleName']) ? $_POST['activePrincipleName'] : null);
    $effects = (isset($_POST['effects']) ? $_POST['effects'] : null);
    $sideEffects = (isset($_POST['sideEffects']) ? $_POST['sideEffects'] : null);
    unset($_GET['insertActivePrinciple']);
    $success = false;
    try {
        $activePrinciplesManager = new ActivePrinciplesManager();
        if($name != null) {
            $success = $activePrinciplesManager->insertActivePrinciple([
                'name' => $name], $effects, $sideEffects);
        }
    } catch(Exception $e) {
        $htmlPage = str_replace('<errName />', '<strong class="formErrors">'.$e->getMessage().'</strong>', $htmlPage);
    } finally {
        if($name == null)
            $htmlPage = str_replace('<errName />', '<strong class="formErrors">Inserire un nome</strong>', $htmlPage);
        else if($success) $htmlPage = str_replace('<success />', '<strong class="formSuccess">Valore inserito</strong>', $htmlPage);
        else str_replace('<success />', '<strong class="formErrors">Inserimento fallito</strong>', $htmlPage);
        echo $htmlPage;
    }
} else if(isset($_POST['submitCategory'])) {
    ob_start();
    $_GET['insertCategory']='Inserisci+categoria';
    include 'insertCharacteristics.php';
    $htmlPage = ob_get_clean();
    $name = (isset($_POST['categoryName']) ? $_POST['categoryName'] : null);
    unset($_GET['insertCategory']);
    $success = false;
    try {
        $categoriesManager = new CategoriesManager();
        if($name != null)
            $success = $categoriesManager->insertCategory($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<errName />', '<strong class="formErrors">'.$e->getMessage().'</strong>', $htmlPage);
    } finally {
        if($name == null)
            $htmlPage = str_replace('<errName />', '<strong class="formErrors">Inserire un nome</strong>', $htmlPage);
        else if($success) $htmlPage = str_replace('<success />', '<strong class="formSuccess">Valore inserito</strong>', $htmlPage);
        else str_replace('<success />', '<strong class="formErrors">Inserimento fallito</strong>', $htmlPage);
        echo $htmlPage;
    }
} else if(isset($_POST['submitEffect'])) {
    ob_start();
    $_GET['insertEffect']='Inserisci+effetto';
    include 'insertCharacteristics.php';
    $htmlPage = ob_get_clean();
    $name = (isset($_POST['effectName']) ? $_POST['effectName'] : null);
    unset($_GET['insertEffect']);
    $success = false;
    try {
        $activePrinciplesManager = new ActivePrinciplesManager();
        if($name != null)
            $success = $activePrinciplesManager->insertEffect($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<errName />', '<strong class="formErrors">'.$e->getMessage().'</strong>', $htmlPage);
    } finally {
        if($name == null)
            $htmlPage = str_replace('<errName />', '<strong class="formErrors">Inserire un nome</strong>', $htmlPage);
        else if($success) $htmlPage = str_replace('<success />', '<strong class="formSuccess">Valore inserito</strong>', $htmlPage);
        else str_replace('<success />', '<strong class="formErrors">Inserimento fallito</strong>', $htmlPage);
        echo $htmlPage;
    }
} else if(isset($_POST['submitSideEffect'])) {
    ob_start();
    $_GET['insertSideEffect']='Inserisci+effetto+collaterale';
    include 'insertCharacteristics.php';
    $htmlPage = ob_get_clean();
    $name = (isset($_POST['sideEffectName']) ? $_POST['sideEffectName'] : null);
    unset($_GET['insertSideEffect']);
    $success = false;
    try {
        $activePrinciplesManager = new ActivePrinciplesManager();
        if($name != null)
            $success = $activePrinciplesManager->insertSideEffect($name);
    } catch(Exception $e) {
        $htmlPage = str_replace('<errName />', '<strong class="formErrors">'.$e->getMessage().'</strong>', $htmlPage);
    } finally {
        if($name == null)
            $htmlPage = str_replace('<errName />', '<strong class="formErrors">Inserire un nome</strong>', $htmlPage);
        else if($success) $htmlPage = str_replace('<success />', '<strong class="formSuccess">Valore inserito</strong>', $htmlPage);
        else str_replace('<success />', '<strong class="formErrors">Inserimento fallito</strong>', $htmlPage);
        echo $htmlPage;
    }
}

