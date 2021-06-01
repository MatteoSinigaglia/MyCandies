<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'Exceptions'.DS.'DBException.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Product.php';
require_once MYCANDIES_PATH . DS . 'Entities' . DS . 'Image.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'CategoriesManager.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'ProductsManager.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'ActivePrinciplesManager.php';
require_once MYCANDIES_PATH . DS . 'Exceptions' . DS . 'EntityException.php';
require_once LIB_PATH . DS . 'functions.php';
require_once LIB_PATH . DS . 'productsForm.php';

use DB\Exceptions\DBException;
use MyCandies\Controllers\ProductsManager;
use MyCandies\Controllers\CategoriesManager;
use MyCandies\Exceptions\EntityException;
use MyCandies\Controllers\ActivePrinciplesManager;

$categoryManager = null;
$activePrincipleManager = null;
try {
    $categoryManager = new CategoriesManager();
    $activePrinciplesManager = new ActivePrinciplesManager();
    $activePrinciples = $activePrinciplesManager->getActivePrinciples();
    $categories = $categoryManager->getCategories();
} catch (Exception $e) {
    header('location:'. MODEL_PATH . DS .'home.php');
    die();
}

$htmlPage = file_get_contents(VIEW_PATH . DS . "inserisciProdotto_dashboard.html");
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
    $success = $insertProduct->insertProduct($data, (empty($data['active_principle_id']) ? null : $data['active_principle_id']), (empty($data['percentage']) ? null : $data['percentage']));
} catch(DBException $e) {
    $result .= '<p class="formErrors">'.$e->getMessage().'</p>';
} catch(EntityException $e) {
    $errOnFields = array_merge($errOnFields, $e->getErrors());
} finally {
    if($success) {
        $htmlPage = str_replace('<error_overall />', '<p class="formSuccess">Prodotto caricato con successo</p>', $htmlPage);
    } else {
        $htmlPage = str_replace('<error_overall />', '<p class="formErrors">'.($result == '' ? 'Ci sono errori nel form di inserimento' : $result).'</p>', $htmlPage);
        if(!empty($errOnFields)) {
            foreach($errOnFields as $key => $value) {
                $htmlPage = str_replace('<error_' . $key . ' />', '<p class="formErrors">' . $value . '</p>', $htmlPage);
            }
        }
    }
    $htmlPage = noFormErrors($htmlPage);
    echo $htmlPage;
}
