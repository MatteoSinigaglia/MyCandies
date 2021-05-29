<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';

use MyCandies\Controllers\ProductsManager;

$htmlPage = file_get_contents(VIEW_PATH. DS . "prodotti_dashboard.html");
$productManager = new ProductsManager();
$productList = array();
try {
    $productList = $productManager->getProducts();
} catch (Exception $e) {
    $htmlPage = str_replace("<insertRow />", '<strong class="formErrors">Errore nel caricamento</strong>', $htmlPage);
    echo $htmlPage;
    die();
}
// carica lista dei prodotti dentro la tabella
$tableRows = "";
if(!empty($productList)) {
    foreach ($productList as $row) {
        $tableRows .=
            "
            <{$row['name']} />
            <tr>
                <td headers=\"name\">
                    {$row['name']}
                </td>
                <td headers=\"price\">
                    {$row['price']}
                </td>
                <td headers=\"quantity\">
                    {$row['availability']}
                </td>
                <td headers=\"actions\">
                    <a href=\"modificaProdotto.php?name={$row['name']}\">Modifica</a>
                </td>
            </tr>";
    }
    $htmlPage = str_replace("<insertRow />", $tableRows, $htmlPage);
} else {
    $htmlPage = str_replace("<insertRow />", '<tr><td colspan="4"><strong class="formErrors">Non sono presenti prodotti nel database</strong></td></tr>', $htmlPage);
}
echo $htmlPage;
