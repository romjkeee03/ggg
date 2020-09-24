<?php

/*

*/
if(!isset($_GET['id'])) {
	header('Location: https://ru.aliexpress.com/');
}

/*

*/
try {
	$item = $_DB->row('SELECT * FROM `items` WHERE `hash` = :hash ORDER BY `id` DESC LIMIT 1', 
		[
			'hash' => $_GET['id']
		]
	);

	if(empty($item)) {
		throw new Exception(true);
	}
} catch(Exception $e) {
	header('Location: https://ru.aliexpress.com/');
}

/* 

*/
if(
	is_bool($ALI = @file_get_contents($item[0]['redirect'])) || !preg_match('/<a\starget="_blank"\shref="(.*)"(.*)>Подробнее\s&\sкупить/', $ALI, $A) || 
	!preg_match('/current-price"(.*)>(.*)<\/div/', $ALI, $AMOUNT) || !isset($A[1]) || !isset($A[2]) || !isset($AMOUNT[1]) || !isset($AMOUNT[2])
) {
	header('Location: https://ru.aliexpress.com/');
}

/* 

*/
$_REPLACES = [
	[
		'search' => '<a target="_blank" href="' . $A[1] . '"'. $A[2] .'>Подробнее & купить</a>',
		'replace' => '<a href="/buy?id='. $item[0]['hash'] .'">Купить сейчас</a>',
	],
	[
		'search' => 'current-price"' . $AMOUNT[1] . '>' . $AMOUNT[2] . '</div',
		'replace' => 'current-price"' . $AMOUNT[1] . '>' . number_format($item[0]['amount'], 2, ',', ' ') . ' руб.</div',
	],
];

/* */
foreach($_REPLACES as $value) {
	$ALI = str_replace($value['search'], $value['replace'], $ALI);
}

/* */
echo $ALI;