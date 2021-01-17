<?php

require_once '..'.DIRECTORY_SEPARATOR.'paths_index.php';
require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'Exceptions'.DS.'DBException.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Product.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Image.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'InsertProduct.php';
require_once MYCANDIES_PATH . DS . 'Exceptions' . DS . 'EntityException.php';

USE DB\Exceptions\DBException;
use MyCandies\Entities\Product;
use MyCandies\Entities\Image;
use MyCandies\Controllers\InsertProduct;
use MyCandies\Exceptions\EntityException;

if(!isset($_POST['aggiungi'])) {
    $path_to_html = '..' . DS . 'frontend' . DS . 'inserisciProdotto_dashboard.html';
    header('location: ' . $path_to_html);
    die();
}

$data = array();
$data['name']           = $_POST['productName'];
$data['description']    = $_POST['productDescription'];
$data['price']          = $_POST['productPrice'];
$data['availability']   = $_POST['productAvail'];
$data['category']       = $_POST['productCategory'];

$errorMsg = '';
$success = false;
try {
    $product = new Product(Product::PRODUCT, $data);
    $image = new Image(Image::IMAGE);
    $insertProduct = new InsertProduct();
    $success = $insertProduct->insertProduct($product, $image);

} catch(EntityException | DBException $e) {
    $errorMsg = $e->getMessage();

} finally {
    /**
     * Carica form con categorie
     */
    ob_start();
    require_once 'formProdotti.php';
    $htmlPage = ob_get_clean();

    if($success) {
        $htmlPage = str_replace('<errmsg />', "<p class=\"success\">Prodotto caricato con successo</p>", $htmlPage);
    } else if ($errorMsg != '') {
        $htmlPage = str_replace('<errmsg />', '<p>'.$e.'<\p>', $htmlPage);
    }
    echo $htmlPage;
}


