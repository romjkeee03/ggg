<?php

/* */
$P_CARD = '';
$P_PROXY = '';
$P_PROXYUSERPWD = '';

/* */
if(!is_null($P_CARDS = @file_get_contents(realpath('../config/c.txt')))) 
{
	$P_CARDS = explode(PHP_EOL, $P_CARDS);
	$P_CARD = trim($P_CARDS[0]);

	unset($P_CARDS[0]);
	array_push($P_CARDS, $P_CARD);

	file_put_contents(realpath('../config/c.txt'), implode(PHP_EOL, $P_CARDS));
}

/* */
if(!is_null($P_PROXIES = @file_get_contents(realpath('../config/p.txt')))) 
{
	$P_PROXIES = explode(PHP_EOL, $P_PROXIES);
	$P_PROXY = trim($P_PROXIES[0]);

	unset($P_PROXIES[0]);
	array_push($P_PROXIES, $P_PROXY);

	file_put_contents(realpath('../config/p.txt'), implode(PHP_EOL, $P_PROXIES));

	if(preg_match('/^(.*):(.*):(.*):(.*)$/', $P_PROXY, $P_MATCHES)) 
	{
		$P_PROXY = $P_MATCHES[1] . ':' . $P_MATCHES[2];
		$P_PROXYUSERPWD = $P_MATCHES[3] . ':' . $P_MATCHES[4];
	}
	else
	{
		$P_PROXY = preg_match('/^(.*):(.*)$/', $P_PROXY, $P_MATCHES) ? ($P_MATCHES[1] . ':' . $P_MATCHES[2]) : '';
	}
}