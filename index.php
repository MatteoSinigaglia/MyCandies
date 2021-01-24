<?php

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('ROOT') || define('ROOT', __DIR__);
defined('VIEW_PATH') || define('VIEW_PATH', ROOT.DS.'frontend');
defined('MODEL_PATH') || define('MODEL_PATH', ROOT.DS.'backend');
defined('LIB_PATH') || define('LIB_PATH', MODEL_PATH.DS.'lib');
defined('MYCANDIES_PATH') || define('MYCANDIES_PATH', MODEL_PATH.DS.'classes'.DS.'MyCandies');

require_once LIB_PATH.DS.'functions.php';
require_once __DIR__.'/backend/config/config.php';

header('location: ./backend/home.php');
die();