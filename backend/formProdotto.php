<?php
    require_once __DIR__ . DIRECTORY_SEPARATOR . "dbConnection.php";
    require_once __DIR__ . DIRECTORY_SEPARATOR . "categories.php";
    use DB\DBAccess as DBAccess;

    $dbaccess = new DBAccess();
    $isSuccessfull = $dbaccess->openDBconnection();

    if($isSuccessfull == false)
        die("Errore nell'apertura del DB");
    else {
        $categories = new Category($dbaccess->getConnection());
        $htmlPage = file_get_contents("../frontend/inserisciProdotto_dashboard.html");
        $placeholder = "<categoryOptions />";
        $htmlPage = $categories->printCategoryOptions($htmlPage, $placeholder);
        echo $htmlPage;
    }
?>