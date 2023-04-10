<?php

use App\Lib\App;

require_once __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('Europe/Berlin');

App::instance(__DIR__)->run();
exit;