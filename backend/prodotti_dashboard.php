<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';
require_once LIB_PATH . DS . 'productListDashboard.php';
require_once LIB_PATH . DS . 'functions.php';

use MyCandies\Controllers\Authentication;

$auth = new Authentication();

if (!isset($_SERVER['HTTP_REFERER']) || !$auth->isAdmin()) {
	header('location: ./home.php');
	die();
}

[
    'DOM' => $htmlPage,
    'productList' => $productList,
] = initProductList();

$htmlPage = insertProductRow($productList, $htmlPage, false);
$htmlPage = noFormErrors($htmlPage);

echo $htmlPage;
