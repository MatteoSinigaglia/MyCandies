<?php

// TODO import di paths_index.php
require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'CategoriesManager.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ActivePrinciplesManager.php';

use MyCandies\Controllers\CategoriesManager;
use MyCandies\Controllers\ActivePrinciplesManager;

try {
    $categoryManager = new CategoriesManager();
    $activePrinciplesManager = new ActivePrinciplesManager();
    $activePrinciples = $activePrinciplesManager->getActivePrinciples();
    $categories = $categoryManager->getCategories();
} catch (Exception $e) {
    header('location: ../backend/home.php');
    die();
}

$htmlPage = file_get_contents(VIEW_PATH . DS . "inserisciProdotto_dashboard.html");
$categoriesOptions = "";

foreach ($categories as $category) {
    if ($category->getName() != null)
        $categoriesOptions .=
            '<option value="'
            . $category->getName()
            . '">'
            . $category->getName()
            . '</ option>';
}

$activePrinciplesOptions = '';
foreach ($activePrinciples as $activePrinciple) {
    if ($activePrinciple->getName() != null)
        $activePrinciplesOptions .=
            '<option value="'
            . $activePrinciple->getName()
            . '">'
            . $activePrinciple->getName()
            . '</ option>';
}
$htmlPage = str_replace("<categoryOptions />", $categoriesOptions, $htmlPage);
$htmlPage = str_replace("<activePrinciples />", $activePrinciplesOptions, $htmlPage);
echo $htmlPage;
