<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbConnection.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "productHandler.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "product.php";


if($isSuccessfull == false)
    die("Errore nell'apertura del DB");
else { // caricamento nel database o mostrare messaggi di errore

    $name = $_GET['name'];

    $productHandler = new ProductHandler($dbaccess->getConnection());

    // esegui e cattura l-output di prodotti_dashboard
    ob_start(); 
    require_once 'prodotti_dashboard.php';
    $htmlPage = ob_get_clean();

    $search = "<table";
    $replace = "
                <form action=\"#\" method=\"post\">
                <table";
    $htmlPage = str_replace($search, $replace, $htmlPage);
    $search = "</table>";
    $replace = "</table>
                </form>";
    $htmlPage = str_replace($search, $replace, $htmlPage);
    
    $pattern = "/$name(.*?)<\/tr>/s";
    $product = $productHandler->getProducts($name)[0];

    $replace = "$name
                <input type=\"hidden\" name=\"modifyName\" value=\"{$row['name']}\">
                </td>
                <td headers=\"price\" scope=\"row\">
                    <input type=\"text\" value=\"{$product['price']}\" id=\"modifyPrice\" name=\"modifyPrice\"/>
                </td>
                <td headers=\"quantity\" scope=\"row\">
                    <input type=\"text\" value=\"{$product['availability']}\" name=\"modifyAvailability\"/>
                </td>
                <td headers=\"actions\" scope=\"row\">
                    <input type=\"button\" value=\"Salva\" name=\"modifyProduct\">
                </td>
            </tr>
        ";
    $htmlPage = preg_replace($pattern, $replace, $htmlPage, 1);
    echo $htmlPage;
}