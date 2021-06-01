<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'CategoriesManager.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ActivePrinciplesManager.php';
require_once LIB_PATH . DS . 'productsForm.php';
require_once LIB_PATH . DS . 'functions.php';

use MyCandies\Controllers\CategoriesManager;
use MyCandies\Controllers\ActivePrinciplesManager;

try {
    $categoryManager = new CategoriesManager();
    $activePrinciplesManager = new ActivePrinciplesManager();
    $activePrinciples = $activePrinciplesManager->getActivePrinciples();
    $categories = $categoryManager->getCategories();
} catch (Exception $e) {
    header('location:'. MODEL_PATH . DS .'home.php');
    die();
}

$htmlPage = file_get_contents(VIEW_PATH . DS . "inserisciProdotto_dashboard.html");
$htmlPage = insertCategoriesIntoForm($categories, $htmlPage);
$htmlPage = insertActivePrinciplesIntoForm($activePrinciples, $htmlPage);
$htmlPage = noFormErrors($htmlPage);

echo $htmlPage;
