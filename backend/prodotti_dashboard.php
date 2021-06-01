<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';

use MyCandies\Controllers\ProductsManager;

$htmlPage = file_get_contents(VIEW_PATH. DS . "prodotti_dashboard.html");
$productList = array();
try {
    $productManager = new ProductsManager();
    $productList = $productManager->getProducts();
} catch (Exception $e) {
    $htmlPage = str_replace("<insertRow />", '<strong class="formErrors">Errore nel caricamento</strong>', $htmlPage);
    echo $htmlPage;
    die();
}

$tableRows = "";
if(!empty($productList)) {
    foreach ($productList as $row) {
        $tableRows .=
            "
            <{$row['name']} />
            <tr>
                <td scope=\"name\">
                    {$row['name']}
                </td>
                <td scope=\"price\">
                    {$row['price']}
                </td>
                <td scope=\"quantity\">
                    {$row['availability']}
                </td>
                <td scope=\"actions\">
                    <a href=\"modificaProdotto.php?name={$row['name']}\">Modifica</a>
                </td>
            </tr>";
    }
    $htmlPage = str_replace("<insertRow />", $tableRows, $htmlPage);
} else {
    $htmlPage = str_replace("<insertRow />", '<tr><td colspan="4"><strong class="formErrors">Non sono presenti prodotti nel database</strong></td></tr>', $htmlPage);
}
echo $htmlPage;
