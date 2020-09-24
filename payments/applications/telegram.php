<?php

class TG 
{
	public function __construct($data = [])
	{
		$this->data = (object) (!is_array($data) ? [] : $data);
	}

	public function is()
	{
		return strlen($this->data->TOKEN) > 0;
	}

	public function sendMessage($id = 0, $message = '')
	{
		if($this->is())
		{
			return $this->query('sendMessage', [
				'chat_id' => $id, 'text' => $message
			]);
		}
	}

	public function query($method = '', $params = [])
	{
		/* */
		$ch = curl_init('https://api.telegram.org/bot' . $this->data->TOKEN . '/'. $method .'?' . http_build_query($params));

		/* */
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		/* */
		if(preg_match('/^(.*):(.*)$/', $this->data->PROXY)) 
		{
			curl_setopt($ch, CURLOPT_PROXY, $this->data->PROXY);

			switch(trim($this->data->PROXYTYPE)) 
			{
				case 'CURLPROXY_HTTP': 
				{
					curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
					break;
				}
				case 'CURLPROXY_HTTPS': 
				{
					curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTPS);
					break;
				}
				case 'CURLPROXY_SOCKS4': 
				{
					curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
					break;
				}
				case 'CURLPROXY_SOCKS4A': 
				{
					curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4A);
					break;
				}
				case 'CURLPROXY_SOCKS5': 
				{
					curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
					break;
				}
				case 'CURLPROXY_SOCKS5_HOSTNAME': 
				{
					curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5_HOSTNAME);
					break;
				}
				case 'CURLPROXY_HTTP_1_0': 
				{
					curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP_1_0);
					break;
				}
			}

			if(preg_match('/^(.*):(.*)$/', $this->data->PROXYUSERPWD)) 
			{
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->data->PROXYUSERPWD);
			}
		}

		/* */
		$response = json_decode(curl_exec($ch));

		/* */
		if(!isset($response->ok) || !$response->ok) {
			throw new Exception(curl_error($ch) . ' - ' . json_encode($response));
		}

		/* */
		curl_close($ch);

		/* */
		return $response;
	}
}