<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Product.php';
require_once LIB_PATH . DS . 'productListDashboard.php';

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
        $errorMsg .= Product::validateAvailability($data['availability']) ?? '<p class="formErrors">'.Product::validateAvailability($data['availability']).'</p>';
        $errorMsg .= Product::validatePrice($data['price']) ?? '<p class="formErrors">'.Product::validatePrice($data['price']).'</p>';
        if($errorMsg == '') {
            $success = $productManager->modifyProduct($data);
        }
        echo $errorMsg;
    } catch (DBException | Exception $e) {
        $errorMsg .= '<p class="formErrors">' . $e->getMessage() . '</p>';
    }
} else if(isset($_POST['deleteProduct'])) {
    $errorMsg = '';
    $data = array();
    $data['name'] = $_POST['modifyName'];
    try {
        $success = $productManager->removeProduct($data['name']);
    } catch (DBException | Exception $e) {
        $errorMsg .= '<p class="formErrors">' . $e->getMessage() . '</p>';
    }
}

[
    'DOM' => $htmlPage,
    'productList' => $productList,
] = initProductList();

$htmlPage = insertProductRow($productList, $htmlPage, false);

if($success == true) {
    $htmlPage = str_replace('<error_overall />', '<p class="formSuccess">Operazione completata con successo</p>', $htmlPage);
} else {
    $htmlPage = str_replace('<error_overall />', $errorMsg, $htmlPage);
}
echo $htmlPage;
