<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'Exceptions'.DS.'DBException.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Product.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Image.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'Authentication.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'CategoriesManager.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'ProductsManager.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'ActivePrinciplesManager.php';
require_once MYCANDIES_PATH . DS . 'Exceptions' . DS . 'EntityException.php';
require_once LIB_PATH . DS . 'functions.php';
require_once LIB_PATH . DS . 'productsFormLib.php';

use DB\Exceptions\DBException;
use MyCandies\Controllers\Authentication;
use MyCandies\Controllers\ProductsManager;
use MyCandies\Exceptions\EntityException;

$auth = new Authentication();

if (!isset($_SERVER['HTTP_REFERER']) || !$auth->isAdmin()) {
	header('location: ./home.php');
	die();
}

[
    'categoryManager' => $categoryManager,
    'activePrinciplesManager' => $activePrinciplesManager,
    'categories' => $categories,
    'activePrinciples' => $activePrinciples,
    'DOM' => $htmlPage,
] = initProductsForm();
$htmlPage = insertCategoriesIntoForm($categories, $htmlPage);
$htmlPage = insertActivePrinciplesIntoForm($activePrinciples, $htmlPage);

if(!isset($_POST['aggiungi'])) {
    echo $htmlPage;
    die();
}

$success = false;
$result = '';
$errOnFields = array();
$data = array();

$data['name']                   = $_POST['productName'];
$data['description']            = $_POST['productDescription'];
$data['price']                  = $_POST['productPrice'];
$data['availability']           = $_POST['productAvail'];
$data['percentage']             = $_POST['productPercentage'];
$data['category_id']            = null;
$data['active_principle_id']    = null;

try {
    if(isset($_POST['productCategory'])) {
        $data['category_id'] = $categoryManager->searchIdByName($_POST['productCategory'])->getId();
    } else $errOnFields['category'] = 'Non è stata scelta nessuna categoria';
    if(isset($_POST['productActivePrinciple'])) {
        $data['active_principle_id'] = $activePrinciplesManager->searchIdByName($_POST['productActivePrinciple'])->getId();
    } else $errOnFields['active_principle_id'] = 'Non è stato scelto nessun principio attivo';
    $insertProduct = new ProductsManager();
    $success = $insertProduct->insertProduct($data, $data['active_principle_id'] ?? null, $data['percentage'] ?? null);
} catch(DBException $e) {
    $result .= '<strong class="formErrors">'.$e->getMessage().'</strong>';
} catch(EntityException $e) {
    $errOnFields = array_merge($errOnFields, $e->getErrors());
} finally {
    if($success) {
        $htmlPage = str_replace('<error_overall />', '<strong class="formSuccess">Prodotto caricato con successo!</strong>', $htmlPage);
    } else {
        $htmlPage = str_replace('<error_overall />', '<strong class="formErrors">'.($result == '' ? 'Ci sono errori nel form di inserimento' : $result).'!</strong>', $htmlPage);
        if(!empty($errOnFields)) {
            foreach($errOnFields as $key => $value) {
                $htmlPage = str_replace('<error_' . $key . ' />', '<strong class="formErrors">' . $value . '!</strong>', $htmlPage);
            }
        }
    }
    $htmlPage = noFormErrors($htmlPage);
    echo $htmlPage;
}
