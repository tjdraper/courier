<?php

if (! defined('COURIER_NAME')) {
	define('COURIER_NAME', 'Courier');
	define('COURIER_VER', '1.0.1');
	define('COURIER_AUTHOR', 'TJ Draper');
	define('COURIER_AUTHOR_URL', 'https://buzzingpixel.com');
	define('COURIER_DESC', 'Manage mailing lists/groups and deliver email via Mandrill');
	define('COURIER_PATH', PATH_THIRD . 'courier/');
}

$config['name'] = COURIER_NAME;
$config['version'] = COURIER_VER;