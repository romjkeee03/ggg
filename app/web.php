<?php

return [
	'TELEGRAM' => [
		'TOKEN' => '', /* ТОКЕН от TG бота */
	],

	'DB' => [
		'HOST' => '', /* Данные от базы данных MYSQL - Хостинг */
		'NAME' => '', /* Данные от базы данных MYSQL - Наименование таблицы */
		'USER' => '', /* Данные от базы данных MYSQL - Имя пользователя */
		'PASS' => '', /* Данные от базы данных MYSQL - Пароль */
	],

	'ROUTE' => [
		[
			'pattern' => '/^\/item$/',
			'views' => 'item',
		],
		[
			'pattern' => '/^\/create$/',
			'views' => 'create',
		],
		[
			'pattern' => '/^\/buy$/',
			'views' => 'buy',
		],
		[
			'pattern' => '/^\/bot$/',
			'views' => 'bot',
		],
	],
];