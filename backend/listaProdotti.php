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
    $productCards .=
                '<div class="product-card">
                    <div class="product-image">
                        <img src="' . $product['img_path'] . '" alt="Immagine del prodotto '.$product['name'].'">
                    </div>
                    <div class="product-info">
                        <a href="'."prodotto.php?id=".$product['id'].'">'.$product['name'].'</a>
                        <p>€'.$product['price'].'</p>
                    </div>
                </div>';
}

$htmlPage = str_replace("<listOfProducts />", $productCards, $htmlPage);
echo $htmlPage;