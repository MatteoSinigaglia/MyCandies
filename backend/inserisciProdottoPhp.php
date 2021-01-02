<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . "dbConnection.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "product.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "categories.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "productHandler.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "images.php";
use DB\DBAccess as DBAccess;

$dbaccess = new DBAccess();
$isSuccessfull = $dbaccess->openDBconnection();
$connection = $dbaccess->getConnection();

if($isSuccessfull == false)
    die("Errore nell'apertura del DB");
else { // caricamento nel database o mostrare messaggi di errore
    if(isset($_POST['aggiungi'])) {
        $name       = $_POST['productName'];
        $descr      = $_POST['productDescription'];
        //$img        = $_POST['productImage'];
        $price      = $_POST['productPrice'];
        $avail      = $_POST['productAvail'];
        $category   = $_POST['productCategory'];
        $activepr   = $_POST['productName'];
    }

    $product = new Product();
    $cat = new Category($connection);
    $product->setCategory_id($cat->getId($category))
            ->setName($name)
            ->setDescription($descr)
            ->setPrice($price)
            ->setAvailability($avail)
            ->setLinked_category();
    
    $productHandler = new ProductHandler($dbaccess->getConnection(), $product);
    $image = new Images($connection);
    $image->validateUpload();

    $htmlPage = file_get_contents(".." . DIRECTORY_SEPARATOR . "frontend" . DIRECTORY_SEPARATOR . "inserisciProdotto_dashboard.html");
    if(!$productHandler->insertProduct($product)) {
        $htmlPage = str_replace('<errmsg />', strval($product), $htmlPage);
        $categories = new Category($dbaccess->getConnection());
        $htmlPage = $categories->printCategoryOptions($htmlPage, "<categoryOptions />");
    } else {
        $htmlPage = str_replace('<errmsg />', "<p>Prodotto caricato con successo</p>", $htmlPage);
    }
    echo $htmlPage;
}
?>