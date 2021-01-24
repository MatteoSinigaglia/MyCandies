<?php

use MyCandies\Controllers\Authentication;

require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';

$auth = new Authentication();

$auth->logout();
header('location: ./home.php');
die();
