<?php

// TODO import di paths_index.php
require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';

use MyCandies\Controllers\ProductsManager;

try {
    $productsManager = new ProductsManager();
    $productsList = null;
    if(isset($_GET['productSearchBar'])) {
        $productsList = $productsManager->searchProduct($_GET['productSearchBar']);
    } else $productsList = $productsManager->getProducts();
} catch (Exception $e) {
    // load 404 page, TODO
    header("location: ../backend/home.php");
    die();
}

$htmlPage = file_get_contents(VIEW_PATH . DS . "listaProdotti.html");

$productCards = '';
foreach ($productsList as $product) {
    $productCards .=
                '<div class="product-card">
                    <div class="product-image">
                        <img src="'.$product['img_path'].'"  alt="Immagine del prodotto '.$product['name'].'" />
                    </div>
                    <div class="product-info">
                        <h1><a href="../'."backend".DS."prodotto.php?id=".$product['id'].'">'.$product['name'].'</a></h1>
                        <p>€ '.$product['price'].'</p>
                    </div>
                </div>';
}

$htmlPage = str_replace("<listOfProducts />", $productCards, $htmlPage);
echo $htmlPage;
