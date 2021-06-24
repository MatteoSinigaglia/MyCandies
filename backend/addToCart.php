<?php

use MyCandies\Controllers\Authentication;
use MyCandies\Controllers\ShopManager;

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';

if (!isset($_SERVER['HTTP_REFERER'])) {
	header('location: ./home.php');
	die();
}

if (!isset($_GET['id'])) {
	header('location: ' . '.' . DS . 'listaProdotti.php');
	die();
} else {
	require_once MYCANDIES_PATH . DS . 'Controllers' . DS . 'ShopManager.php';
	$shop = new ShopManager();
	$shop->addToCart($_GET);
	header('location: '.'.'.DS.'prodotto.php?id='.$_GET['id']);
	die();
}