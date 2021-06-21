<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';

use MyCandies\Controllers\Authentication;
use MyCandies\Controllers\ShopManager;

if (!isset($_SERVER['HTTP_REFERER'])) {
	header('location: .' . DS . 'home.php');
	die();
}

require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'Authentication.php';
require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'ShopManager.php';

$auth = new Authentication();
$shop = new ShopManager();

switch ($_GET['action']) {
	case 'decrease':
		$shop->decreaseProductQuantity((int)$_GET['id']);
		break;
	case 'increase':
		$shop->addToCart($_GET);
		break;
	case 'remove':
		$shop->removeProduct((int)$_GET['id']);
		break;
}

header('location: .' . DS . 'carrello.php');
die();
