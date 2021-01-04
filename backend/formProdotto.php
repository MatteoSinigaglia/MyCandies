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
        $htmlPage = file_get_contents(".." . DIRECTORY_SEPARATOR ."frontend" . DIRECTORY_SEPARATOR . "inserisciProdotto_dashboard.html");
        $htmlPage = $categories->printCategoryOptions($htmlPage, "<categoryOptions />");
        echo $htmlPage;
    }
?>