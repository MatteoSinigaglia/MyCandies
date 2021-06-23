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

if  ($auth->isLoggedIn()) {
	$DOM = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="fa fa-sign-out buttons"><span xml:lang="en"> Logout</span></a>', $DOM);
} else
	$DOM = str_replace('<a_auth_state />', '<a href="./formCliente.php" id="loginButton" class="fa fa-sign-in buttons"><span> Accedi</span></a>', $DOM);

//  Menu setup
$DOM = str_replace('<dashboard />', ($auth->isAdmin()
	?
	'<li><a href="../backend/inserisciProdotto.php">Gestione</a></li>'
	:
	''), $DOM);

if (!isset($cart) || $cart['info']->getTotal() == 0) {

	$DOM = str_replace('<productsInCart />', '<tr>
				<td scope="row" title="Nome"></td>
				<td scope="row" title="Codice"></td>
				<td scope="row" title="Quantità"></td>
				<td scope="row" title="Azioni"></td>
				<td scope="row" title="Totale prodotto"></td>
			</tr>', $DOM);
    $DOM = str_replace('<total />', '<tr><td scope="row" title="Totale carello">&euro;0</td></tr>', $DOM);
} else {
	$productsInCart = '';
	$products = $shop->getProducts();
	foreach ($products as $product) {
		$productId = $product->getId();
		$decreaseProductQuantity = ($shop->isMinimumQuantity($cart[$productId]) ?
            '<span class="fa fa-minus disabledQuantity buttons"><span class="helps">Quantità minima selezionata</span></span>' :
            '<a href="../backend/actionsFromCart.php?action=decrease&id='.$productId.'" class="fa fa-minus buttons"><span class="helps">Diminuisci quantità prodotto</span></a>');
		$increaseProductQuantity = ($shop->isMaximumQuantity($cart[$productId], $product) ?
            '<span class="fa fa-plus disabledQuantity buttons"><span class="helps">Quantità massima selezionata</span></span>' :
            '<a href="../backend/actionsFromCart.php?action=increase&id='.$productId.'" class="fa fa-plus buttons"><span class="helps">Aumenta quantità prodotto</span></a>');
		$productsInfos =
			'<tr>
				<td scope="row" title="Nome">'.$product->getName().'</td>
				<td scope="row" title="Codice">'.$productId.'</td>
				<td scope="row" title="Quantità">'.$decreaseProductQuantity.$cart[$productId].$increaseProductQuantity.'</td>
				<td scope="row" title="Azioni"><a href="../backend/actionsFromCart.php?action=remove&id='.$productId.'" class="fa fa-remove buttons"><span class="helps">Rimuovi prodotto dal carrello</span> </a></td>
				<td scope="row" title="Totale prodotto">&euro;'.(float)$cart[$productId]*(float)$product->getPrice().'</td>
			</tr>';
		$productsInCart .= $productsInfos;
	}
	$DOM = str_replace('<productsInCart />', $productsInCart, $DOM);
	$total = (isset($cart['info']) ? round($cart['info']->getTotal(), 2) : 0);
	$DOM = str_replace('<total />', '<td scope="row" title="Totale carello">&euro;'.$total.'</td>', $DOM);
}

if (isset($_SESSION['log'])) {

	$class = (isset($_SESSION['logtype']) && $_SESSION['logtype'] === 'success' ? 'formSuccess' : 'formErrors');
	$statusLog = "
		<div>
			<strong class='{$class}'>{$_SESSION['log']}</strong>
		</div>";

    unset($_SESSION['log']);
    unset($_SESSION['logtype']);
} else {
	$statusLog = '';
}

$DOM = str_replace('<status-log />', $statusLog, $DOM);

echo $DOM;
