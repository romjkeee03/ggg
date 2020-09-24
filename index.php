<?php
<!-- TW1NZ -->

/* 

*/
require_once(realpath('./app/database.php'));

/* 

*/
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

/* 

*/
date_default_timezone_set('Europe/Moscow');

/* 

*/
session_start();

/*

*/
$_URI = urldecode(
	parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if(substr($_URI, -1) === '/') {
	$_URI = substr($_URI, 0, -1);
}

$_URI = str_replace('/index.php', '', $_URI);

/*

*/
$_DB = new Database(
	($_WEB = require_once(realpath('./app/web.php')))['DB']
);

/* 

*/
$_PROTOCOL = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ? 'https' : 'http';

/*

*/
foreach($_WEB['ROUTE'] as $value)
{
	if(preg_match($value['pattern'], $_URI))
	{
		/* */
		$path = realpath('./views/' . str_replace('.', '/', $value['views']) . '.php');

		/* */
		if(file_exists($path))
		{
			die(
				require_once($path)
			);
		}
	}
}

/*

*/
header('Location: https://ru.aliexpress.com/');