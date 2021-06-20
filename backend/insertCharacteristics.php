<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';
require_once LIB_PATH . DS . 'insertCharacteristicsLib.php';

use MyCandies\Controllers\Authentication;

$auth = new Authentication();

if (!isset($_SERVER['HTTP_REFERER']) || !$auth->isAdmin()) {
	header('location: ./home.php');
	die();
}

[
    'DOM' => $htmlPage,
    'pattern' => $pattern
] = initInsertCharacteristics();

if(isset($_GET['insertActivePrinciple'])) {
    $htmlPage = insertActivePrinciple($htmlPage, $pattern);
} else if(isset($_GET['insertEffect'])) {
    $htmlPage = insertEffect($htmlPage, $pattern);
} else if(isset($_GET['insertSideEffect'])) {
    $htmlPage = insertSideEffect($htmlPage, $pattern);
} else if(isset($_GET['insertCategory'])) {
    $htmlPage = insertCategory($htmlPage, $pattern);
}

echo $htmlPage;
