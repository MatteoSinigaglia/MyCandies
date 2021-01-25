<?php

use MyCandies\Controllers\Authentication;
use MyCandies\Controllers\ShopManager;

require_once __DIR__.'/../paths_index.php';

if (!isset($_SERVER['HTTP_REFERER'])) {
//	Php file has been accessed irregularly
	header('location: ./home.php');
	die();
}

//require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';
//$auth = new Authentication();

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