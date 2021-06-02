<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';

use MyCandies\Controllers\Authentication;
use MyCandies\Controllers\ShopManager;


require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ShopManager.php';

$auth = new Authentication();
$shop = new ShopManager();


$cart = $shop->getCart();

$DOM = file_get_contents('../frontend/carrello.html');

if (!isset($cart)) {
	$DOM = str_replace('<productsInCart />', 'vuoto', $DOM);
} else {
	$productsInCart = '';
	$products = $shop->getProducts();
	foreach ($products as $product) {
		$productId = $product->getId();
		$productsInfos =
			'<tr>
				<td scope="row" title="Nome">'.$product->getName().'</td>
				<td scope="row" title="Codice">'.$productId.'</td>
				<td scope="row" title="Quantità"><a href="../backend/actionsFromCart.php?action=decrease&id='.$productId.'" class="fa fa-minus buttons"><span class="helps">Diminuisci quantità prodotto</span></a>'.$cart[$productId].'<a href="../backend/actionsFromCart.php?action=increase&id='.$productId.'" class="fa fa-plus buttons"><span class="helps">Aumenta quantità prodotto</span></a></td>
				<td scope="row" title="Azioni"><a href="../backend/actionsFromCart.php?action=remove&id='.$productId.'" class="fa fa-remove buttons"><span class="helps">Rimuovi prodotto dal carrello</span> </a></td>
				<td scope="row" title="Totale prodotto">'.(float)$cart[$productId]*(float)$product->getPrice().'</td>
			</tr>';
		$productsInCart .= $productsInfos;
	}
	$DOM = str_replace('<productsInCart />', $productsInCart, $DOM);
	$DOM = str_replace('<total />', '<td scope="col">'.(isset($cart['info']) ? round($cart['info']->getTotal(), 2) : '0').'</td>', $DOM);
}


echo $DOM;