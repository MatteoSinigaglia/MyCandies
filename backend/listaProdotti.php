<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'CategoriesManager.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';

use MyCandies\Controllers\ProductsManager;
use MyCandies\Controllers\CategoriesManager;
use MyCandies\Controllers\Authentication;

$auth = new Authentication();

$htmlPage = file_get_contents(VIEW_PATH . DS . "listaProdotti.html");
if  ($auth->isLoggedIn()) {
    $htmlPage = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="fa fa-sign-out buttons"><span xml:lang="en"> Logout</span></a>', $htmlPage);
} else
    $htmlPage = str_replace('<a_auth_state />', '<a href="./formCliente.php" id="loginButton" class="fa fa-sign-in buttons"><span> Accedi</span></a>', $htmlPage);

//  Menu setup
$htmlPage = str_replace('<dashboard />', ($auth->isAdmin()
	?
	'<li><a href="../backend/inserisciProdotto.php">Gestione</a></li>'
	:
	''), $htmlPage);

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

$categories = '';
foreach ($categoriesList as $category) {
    if(isset($_GET['category']) && $category->getId() == $_GET['category'])
        $categories .=
            '<span class="selectedButton buttons">'.$category->getName().'</span>
        ';
    else
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

