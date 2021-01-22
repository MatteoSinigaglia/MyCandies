<?php
//defined('DS') || define('DS', DIRECTORY_SEPARATOR);
//defined('ROOT') || define('ROOT', __DIR__);
//defined('VIEW_PATH') || define('VIEW_PATH', ROOT.DS.'frontend'.DS);
//defined('MODEL_PATH') || define('MODEL_PATH', ROOT.DS.'backend'.DS);
//defined('LIB_PATH') || define('LIB_PATH', MODEL_PATH.DS.'lib'.DS);

//require_once LIB_PATH.'functions.php';
//require_once __DIR__.'/backend/config/config.php';

//$page = get('home', '404');
header('location: ./backend/home.php');
die();