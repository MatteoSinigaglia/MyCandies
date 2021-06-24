<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Administration.php';

use MyCandies\Controllers\Authentication;
use MyCandies\Controllers\Administration;

$auth = new Authentication();

if (!isset($_SERVER['HTTP_REFERER']) || !$auth->isAdmin()) {
	header('location: ./home.php');
	die();
}

$admin = new Administration();
$transactionsData = $admin->getTransactionsData();
$DOM = file_get_contents('..'.DS.'frontend'.DS.'riepilogoAcquisti_dashboard.html');

$rows = '';
foreach ($transactionsData as $transaction) {
	$row = '
		<tr>
			<td scope="row" title="Utente">'.$transaction['user'].'</td>
			<td scope="row" title="Codice">'.$transaction['cartId'].'</td>
			<td scope="row" title="Data e ora">'.$transaction['datetime'].'</td>
			<td scope="row" title="Totale">'.$transaction['total'].'</td>
		</tr>';
	$rows .= $row;
}
$DOM = str_replace('<table-data />', $rows, $DOM);
echo $DOM;
