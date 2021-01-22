<?php

//TODO rimuovere include a paths
require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';

use MyCandies\Controllers\ProductsManager;

$success = false;
$errorMsg = '';
$productManager = new ProductsManager();

if(isset($_POST['modifyProduct'])) {
    $data = array();
    $data['price'] = $_POST['modifyPrice'];
    $data['availability'] = $_POST['modifyAvailability'];
    $data['name'] = $_POST['modifyName'];
    try {
        $success = $productManager->modifyProduct($data);
    } catch (\DB\Exceptions\DBException | Exception $e) {
        $errorMsg .= '<p>' . $e->getMessage() . '</p>';
    }
} else if(isset($_POST['deleteProduct'])) {
    $errorMsg = '';
    $data = array();
    $data['name'] = $_POST['modifyName'];
    try {
        $success = $productManager->removeProduct($data['name']);
    } catch (\DB\Exceptions\DBException | Exception $e) {
        $errorMsg .= '<p>' . $e->getMessage() . '</p>';
    }
}

ob_start();
include 'prodotti_dashboard.php';
$htmlPage = ob_get_clean();

if($success == true) {
    $htmlPage = str_replace('<messages />', '<p>Operazione completata con successo</p>', $htmlPage);
} else {
    $htmlPage = str_replace('<messages />', $errorMsg, $htmlPage);

}
echo $htmlPage;
