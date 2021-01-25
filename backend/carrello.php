<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';


use MyCandies\Controllers\Authentication;
use MyCandies\Controllers\ShopManager;
use MyCandies\Controllers\ProductsManager;


require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';
$auth = new Authentication();

require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ShopManager.php';
$shop = new ShopManager();
$cart = $shop->getCart();

$DOM = file_get_contents('../frontend/carrello.html');

//require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';
//$productManager = new ProductsManager();
if (!isset($cart)) {
	$DOM = str_replace('<productsInCart />', 'vuoto', $DOM);
} else {
	$productsIds = $cart;
	unset($productsIds['info']);
	var_dump($productsIds);
	$productsInCart = '';
	$products = $shop->getProducts();
//	foreach ($products as $product) {
		var_dump($products);
//	}
//}
	foreach ($products as $product) {
		echo $product->getId();
		$productsInfos =
			'<tr>
				<td headers="productName" title="Nome">'.$product->getName().'</td>
				<td headers="productCode" title="Codice">'.$product->getId().'</td>
				<td headers="quantity" title="Quantità">'.$cart[$product->getId()].'</td>
				<td headers="actions" title="Azioni">'.'</td>
				<td headers="total" title="Totale prodotto">'.(float)$cart[$product->getId()]*(float)$product->getPrice().'</td>
			</tr>';
		$productsInCart .= $productsInfos;
	}
	$DOM = str_replace('<productsInCart />', $productsInCart, $DOM);
}


echo $DOM;