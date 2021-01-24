<?php

// TODO import di paths_index.php
require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'CategoriesManager.php';

use MyCandies\Controllers\ProductsManager;
use MyCandies\Controllers\CategoriesManager;

try {
    $productsManager = new ProductsManager();
    $categoriesManager = new CategoriesManager();
    $productsList = null;
    $categoriesList = $categoriesManager->getCategories();
    if(isset($_GET['productSearchBar'])) {
        $productsList = $productsManager->searchProduct($_GET['productSearchBar']);
    } else if(isset($_GET['category'])) {
        $productsList = $productsManager->findProductsByCategory($_GET['category']);
    } else $productsList = $productsManager->getProducts();
} catch (Exception $e) {
    header("location: ../backend/home.php");
    die();
}
$htmlPage = file_get_contents(VIEW_PATH . DS . "listaProdotti.html");
$categories = '';
foreach ($categoriesList as $category) {
    $categories .=
        '<a href="../backend/listaProdotti.php?category='.$category->getId().'" class="buttons">'.$category->getName().'</a>
        ';
}
$htmlPage = str_replace("<categories />", $categories, $htmlPage);
$productCards = '';
foreach ($productsList as $product) {
    $productCards .=
                '<div class="product-card">
                    <div class="product-image">
                        <img src="'.$product['img_path'].'"  alt="Immagine del prodotto '.$product['name'].'" />
                    </div>
                    <div class="product-info">
                        <h2><a href="../'."backend".DS."prodotto.php?id=".$product['id'].'" class="paragraphLink">'.$product['name'].'</a></h2>
                        <p>€ '.$product['price'].'</p>
                    </div>
                </div>';
}
$htmlPage = str_replace("<listOfProducts />", $productCards, $htmlPage);
echo $htmlPage;
