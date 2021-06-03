<?php

require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';

use MyCandies\Controllers\ProductsManager;

function initProductList(): array {
    $htmlPage = file_get_contents(VIEW_PATH. DS . "prodotti_dashboard.html");
    try {
        $productManager = new ProductsManager();
        $productList = $productManager->getProducts();
    } catch (Exception $e) {
        http_response_code(404);
        include(MODEL_PATH . DS . 'error404.php');
        die();
    }
    return [
      'DOM' => $htmlPage,
      'productList' => $productList,
    ];
}

function insertProductRow(array $productList, String $DOM, bool $insertTag): String {
    $tableRows = "";
    if(!empty($productList)) {
        foreach ($productList as $row) {
            $tag = $insertTag ? "<modify_{$row['name']} />" : "";
            $tableRows .=
                "
            {$tag}
            <tr>
                <td scope=\"row\" title=\"Nome\">
                    {$row['name']}
                </td>
                <td scope=\"row\" title=\"Prezzo\">
                    {$row['price']}
                </td>
                <td scope=\"row\" title=\"Quantità\">
                    {$row['availability']}
                </td>
                <td scope=\"row\" title=\"Azioni\">
                    <a href=\"modificaProdotto.php?name={$row['name']}\">Modifica</a>
                </td>
            </tr>";
        }
        return str_replace("<insertRow />", $tableRows, $DOM);
    } else {
        return str_replace("<insertRow />", '<tr><td colspan="4"><p class="formErrors">Non sono presenti prodotti nel database</p></td></tr>', $DOM);
    }
}
