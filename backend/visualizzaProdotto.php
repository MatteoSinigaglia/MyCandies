<?php

// TODO import di paths_index.php
require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';

use MyCandies\Controllers\ProductsManager;

try {
    $productsManager = new ProductsManager();
    $productsList = $productsManager->getProducts();
} catch (Exception $e) {
    // load 404 page, TODO
    echo $e->getMessage();
    die();
}

$htmlPage = file_get_contents(VIEW_PATH . DS . "listaProdotti.html");

$productCards = '';
foreach ($productsList as $product) {
    // TODO aggiungere alt a immagine
    $productCards .=
                '<div class="product-card">
                    <div class="product-image">
                        <img src="' . $product['img_path'] . '">
                    </div>
                    <div class="product-info">
                        <h5>'.$product['name'].'</h5>
                        <h6>€'.$product['price'].'</h6>
                    </div>
                </div>';
}

$htmlPage = str_replace("<listOfProducts />", $productCards, $htmlPage);
echo $htmlPage;