<?php

require_once '..'.DIRECTORY_SEPARATOR.'paths_index.php';
require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'Exceptions'.DS.'DBException.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Product.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Image.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'CategoriesManager.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'ProductsManager.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'ActivePrinciplesManager.php';
require_once MYCANDIES_PATH . DS . 'Exceptions' . DS . 'EntityException.php';

USE DB\Exceptions\DBException;
use MyCandies\Entities\Product;
use MyCandies\Entities\Image;
use MyCandies\Controllers\ProductsManager;
use MyCandies\Controllers\CategoriesManager;
use MyCandies\Exceptions\EntityException;
use MyCandies\Controllers\ActivePrinciplesManager;

ob_start();
require_once 'formProdotto.php';
$htmlPage = ob_get_clean();

/*if(!isset($_POST['aggiungi'])) {
    echo $htmlPage;
    die();
}*/

$success = false;
$errorMsg = '';
$categoryManager = new CategoriesManager();
$activePrincipleManager = new ActivePrinciplesManager();
$data = array();

$data['name']           = $_POST['productName'];
$data['description']    = $_POST['productDescription'];
$data['price']          = $_POST['productPrice'];
$data['availability']   = $_POST['productAvail'];
$data['percentage']     = $_POST['productPercentage'];

try {
    $data['category_id'] = $categoryManager->searchIdByName($_POST['productCategory'])->getId();
    $data['active_principle_id'] = $activePrincipleManager->searchIdByName($_POST['productActivePrinciple'])->getId();
} catch (DBException $e) {
    $errorMsg .= '<li>'.$e->getMessage().'</li>';
}

try {
    $product = new Product(Product::PRODUCT, $data);
    $image = new Image(Image::IMAGE);
    $insertProduct = new ProductsManager();
    $success = $insertProduct->insertProduct($product, $image, $data['active_principle_id'], $data['percentage']);

} catch(EntityException | DBException $e) {
    $errorMsg .= '<li>'.$e->getMessage().'</li>';

} finally {
    /**
     * Carica form con categorie e principi attivi
     */

    if($success) {
        $htmlPage = str_replace('<errmsg />', "<p class=\"success\">Prodotto caricato con successo</p>", $htmlPage);
    } else {
        $htmlPage = str_replace('<errmsg />', '<ul class=\"failure\">'.$errorMsg.'</ul>', $htmlPage);
    }
    echo $htmlPage;
}


