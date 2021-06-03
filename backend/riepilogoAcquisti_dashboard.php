<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';

use MyCandies\Controllers\Authentication;

$auth = new Authentication();

if (!isset($_SERVER['HTTP_REFERER']) || !$auth->isAdmin()) {
	header('location: ./home.php');
	die();
}

$DOM = file_get_contents('..'.DS.'frontend'.DS.'riepilogoAcquisti_dashboard.html');

echo $DOM;
