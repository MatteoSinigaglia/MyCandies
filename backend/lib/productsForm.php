<?php

require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'CategoriesManager.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'ActivePrinciplesManager.php';

use MyCandies\Controllers\CategoriesManager;
use MyCandies\Controllers\ActivePrinciplesManager;

function initProductsForm(): array {
    try {
        $categoryManager = new CategoriesManager();
        $activePrinciplesManager = new ActivePrinciplesManager();
        $activePrinciples = $activePrinciplesManager->getActivePrinciples();
        $categories = $categoryManager->getCategories();
        return [
            'categoryManager' => $categoryManager,
            'activePrinciplesManager' => $activePrinciplesManager,
            'categories' => $categories,
            'activePrinciples' => $activePrinciples,
            'DOM' => file_get_contents(VIEW_PATH . DS . "inserisciProdotto_dashboard.html"),
        ];
    } catch (Exception $e) {
        // TODO pagina 404
        header('location:'. MODEL_PATH . DS .'home.php');
        die();
    }
}

function insertCategoriesIntoForm($categories, $DOM): String {
    $categoriesOptions = "";
    foreach ($categories as $category) {
        if ($category->getName() != null)
            $categoriesOptions .=
                "<option value=\"{$category->getName()}\">{$category->getName()}</option> \n";
    }
    return str_replace("<categoryOptions />", $categoriesOptions, $DOM);
}

function insertActivePrinciplesIntoForm($activePrinciples, $DOM): String {
    $activePrinciplesOptions = '';
    foreach ($activePrinciples as $activePrinciple) {
        if ($activePrinciple->getName() != null)
            $activePrinciplesOptions .=
                "<option value=\"{$activePrinciple->getName()}\">{$activePrinciple->getName()}</option> \n";
    }
    return str_replace("<activePrinciples />", $activePrinciplesOptions, $DOM);
}
