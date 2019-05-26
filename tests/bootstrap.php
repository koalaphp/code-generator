<?php
/**
 * Created by PhpStorm.
 * User: laiconglin
 * Date: 26/11/2017
 * Time: 14:00
 */

// timezone init
date_default_timezone_set('Asia/Shanghai');
define('APP_ROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR));

define('OUTPUT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Dao' . DIRECTORY_SEPARATOR . "%dbNamespace%");

define('ENVIRONMENT', 'develop');

$databaseConfig = [
	'name' => 'test',
	'host' => '127.0.0.1',
	'port' => 3306,
	'user' => 'root',
	'pass' => 'ke0vfyex0yrtwjsaw6sazeivnyxegjcg',
	'charset' => 'utf8mb4',
];

// autoload
require APP_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';



