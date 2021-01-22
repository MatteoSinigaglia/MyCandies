<?php

if (isset($_SERVER['HTTP_REFERER'])) {
	header('location: ./home.php');
	die();
}
$DOM = file_get_contents('../frontend/utenti_dashboard.html');



echo $DOM;