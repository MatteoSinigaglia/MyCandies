<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';


use MyCandies\Controllers\ShopManager;
use MyCandies\Controllers\ProductsManager;

require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ShopManager.php';
$shop = new ShopManager();
$cart = $shop->getCart();

//echo $cart;
//var_dump($_SESSION['cart']);
//$DOM = file_get_contents('../frontend/carrello.html');
//
//require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';
//$productManager = new ProductsManager();
//var_dump($_SESSION['cart']);
//if (!isset($cart)) {
//	$DOM = str_replace('<productsInCart />', 'vuoto', $DOM);
//} else {
//	$productsIds = $cart;
//	unset($productsIds['info']);
//	$productsInCart = '';
//	foreach ($productsIds as $productId) {
//		$product = $productManager->getProductById($productId);
//		$productsInfos =
//			'<tr>
//				<td headers="productName" title="Nome" scope="row">'.$product->getName().'</td>
//				<td headers="productCode" title="Codice" scope="row">'.$product->getId().'</td>
//				<td headers="quantity" title="Quantità" scope="row">'.$product->getPrice().'</td>
//				<td headers="actions" title="Azioni" scope="row">'.'</td>
//				<td headers="total" title="Totale prodotto" scope="row">'.(float)$product->getPrice().'</td>
//			</tr>';
//		$productsInCart .= $productsInfos;
//	}
//	$DOM = str_replace('<productsInCart />', $productsInCart, $DOM);
//}
//
//
//echo $DOM;