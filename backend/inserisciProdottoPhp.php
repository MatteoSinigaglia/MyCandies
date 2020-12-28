<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . "dbConnection.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "product.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "categories.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "productHandler.php";
use DB\DBAccess as DBAccess;

$dbaccess = new DBAccess();
$isSuccessfull = $dbaccess->openDBconnection();
$connection = $dbaccess->getConnection();

if($isSuccessfull == false)
    die("Errore nell'apertura del DB");
else {
    if(isset($_POST['aggiungi'])) {
        $name       = $_POST['productName'];
        $descr      = $_POST['productDescription'];
        $img        = $_POST['productImage'];
        $price      = $_POST['productPrice'];
        $avail      = $_POST['productAvail'];
        $category   = $_POST['productCategory'];
        $activepr   = $_POST['productName'];
    } else {
        echo "What happened :-(";
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
    
    echo ($productHandler->insertProduct($product) ? "Prodotto inserito": "Prodotto non inserito");
}
?>