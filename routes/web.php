<?php

use App\Lib\Router;

Router::add('/', 'HomeController@index');
Router::add('POST /month', 'HomeController@month');
Router::add('POST /add-person', 'PersonController@add');
Router::add('POST /times', 'HomeController@times');
