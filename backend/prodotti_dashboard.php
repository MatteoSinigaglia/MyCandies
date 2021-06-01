<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once LIB_PATH . DS . 'productListDashboard.php';
require_once LIB_PATH . DS . 'functions.php';

[
    'DOM' => $htmlPage,
    'productList' => $productList,
] = initProductList();

$htmlPage = insertProductRow($productList, $htmlPage, false);
$htmlPage = noFormErrors($htmlPage);

echo $htmlPage;
