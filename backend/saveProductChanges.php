<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Product.php';

use MyCandies\Controllers\ProductsManager;
use MyCandies\Entities\Product;
use DB\Exceptions\DBException;

$success = false;
$errorMsg = '';
$productManager = new ProductsManager();

if(isset($_POST['modifyProduct'])) {
    $data = array();
    $data['price'] = $_POST['modifyPrice'];
    $data['availability'] = $_POST['modifyAvailability'];
    $data['name'] = $_POST['modifyName'];
    try {
        $errorMsg .= '<strong class="formErrors">'.Product::validateAvailability($data['availability']).'</strong>';
        $errorMsg .= '<strong class="formErrors">'.Product::validatePrice($data['price']).'</strong>';
        if($errorMsg == '')
            $success = $productManager->modifyProduct($data);
    } catch (DBException | Exception $e) {
        $errorMsg .= '<strong class="formErrors">' . $e->getMessage() . '</strong>';
    }
} else if(isset($_POST['deleteProduct'])) {
    $errorMsg = '';
    $data = array();
    $data['name'] = $_POST['modifyName'];
    try {
        $success = $productManager->removeProduct($data['name']);
    } catch (DBException | Exception $e) {
        $errorMsg .= '<strong class="formErrors">' . $e->getMessage() . '</strong>';
    }
}

ob_start();
include 'prodotti_dashboard.php';
$htmlPage = ob_get_clean();

if($success == true) {
    $htmlPage = str_replace('<messages />', '<strong class="formSuccess">Operazione completata con successo</strong>', $htmlPage);
} else {
    $htmlPage = str_replace('<messages />', $errorMsg, $htmlPage);

}
echo $htmlPage;
