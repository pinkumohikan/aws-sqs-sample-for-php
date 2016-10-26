<?php

define('PROJECT_ROOT', __DIR__);

require_once PROJECT_ROOT . '/vendor/autoload.php';

$doetnv = (new \Dotenv\Dotenv(PROJECT_ROOT));
$doetnv->load();
