<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'CategoriesManager.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ActivePrinciplesManager.php';
require_once LIB_PATH . DS . 'productsFormLib.php';
require_once LIB_PATH . DS . 'functions.php';

[
    'categories' => $categories,
    'activePrinciples' => $activePrinciples,
    'DOM' => $htmlPage,
] = initProductsForm();
$htmlPage = insertCategoriesIntoForm($categories, $htmlPage);
$htmlPage = insertActivePrinciplesIntoForm($activePrinciples, $htmlPage);
$htmlPage = noFormErrors($htmlPage);

echo $htmlPage;
