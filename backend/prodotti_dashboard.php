<?php
    require_once __DIR__ . DIRECTORY_SEPARATOR . "dbConnection.php";
    require_once __DIR__ . DIRECTORY_SEPARATOR . "productHandler.php";
    use DB\DBAccess as DBAccess;

    $dbaccess = new DBAccess();
    $isSuccessfull = $dbaccess->openDBconnection();

    if($isSuccessfull == false)
        die("Errore nell'apertura del DB");
    else {
        $htmlPage = file_get_contents(".." . DIRECTORY_SEPARATOR ."frontend" . DIRECTORY_SEPARATOR . "prodotti_dashboard.html");
        $productHandler = new ProductHandler($dbaccess->getConnection());
        $productList = $productHandler -> getProducts();
        // carica lista dei prodotti dentro la tabella
        if(empty($productList)) {
            $htmlPage = str_replace("<insertRow />", "<p>Non sono ancora presenti prodotti</p>", $htmlPage);
        }
        else {
            $tableRows = "";
            foreach($productList as $row) {
                $tableRows .=
                "<tr>
                    <td headers=\"name\" scope=\"row\">
                        {$row['name']}
                    </td>
                    <td headers=\"price\" scope=\"row\">
                        {$row['price']}
                    </td>
                    <td headers=\"quantity\" scope=\"row\">
                        {$row['availability']}
                    </td>
                    <td headers=\"actions\" scope=\"row\">
                        <a href=\"modificaProdotto.php?name={$row['name']}\">Modifica</a>
                    </td>
                </tr>"
                ;
            }
            $htmlPage = str_replace("<insertRow />", $tableRows, $htmlPage);
        }
        echo $htmlPage;
    }
?>