<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';


use MyCandies\Controllers\ShopManager;

$shop = new ShopManager();
$shop->getCart();

$DOM = file_get_contents('../frontend/carrello.html');



echo $DOM;