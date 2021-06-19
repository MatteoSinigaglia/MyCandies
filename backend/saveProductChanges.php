<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';
require_once MYCANDIES_PATH.DS.'Entities'.DS.'Product.php';
require_once LIB_PATH . DS . 'productListDashboardLib.php';

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
        $errorMsg .= Product::validateAvailability($data['availability']) ?? '<strong class="formErrors">'.Product::validateAvailability($data['availability']).'</strong>';
        $errorMsg .= Product::validatePrice($data['price']) ?? '<strong class="formErrors">'.Product::validatePrice($data['price']).'</strong>';
        if($errorMsg == '') {
            $success = $productManager->modifyProduct($data);
        }
        echo $errorMsg;
    } catch (DBException | Exception $e) {
        $errorMsg .= '<strong class="formErrors">' . $e->getMessage() . '!</strong>';
    }
} else if(isset($_POST['deleteProduct'])) {
    $errorMsg = '';
    $data = array();
    $data['name'] = $_POST['modifyName'];
    try {
        $success = $productManager->removeProduct($data['name']);
    } catch (DBException | Exception $e) {
        $errorMsg .= '<strong class="formErrors">' . $e->getMessage() . '!</strong>';
    }
}

[
    'DOM' => $htmlPage,
    'productList' => $productList,
] = initProductList();

$htmlPage = insertProductRow($productList, $htmlPage, false);

if($success == true) {
    $htmlPage = str_replace('<error_overall />', '<strong class="formSuccess">Operazione completata con successo!</strong>', $htmlPage);
} else {
    $htmlPage = str_replace('<error_overall />', $errorMsg, $htmlPage);
}
echo $htmlPage;
