<?php

/*

*/
class Account
{
	public function __construct($chat = NULL, $db = NULL) {
		$this->db = $db;
		$this->chat = $chat;
	}

	public function search() {
		return $this->db->row('SELECT * FROM `accounts` WHERE `chat` = :chat ORDER BY `id` DESC LIMIT 1',[
			'chat' => $this->chat
		]);
	}

	public function update($column = NULL, $value = NULL) {
		return $this->db->prepare('UPDATE `accounts` SET `'. $column .'` = :'. $column .' WHERE `chat` = :chat', [
			$column => $value, 'chat' => $this->chat
		]);
	}

	public function insert($data = []) {
		return $this->db->prepare('INSERT INTO `accounts`(`chat`, `username`) VALUES (:chat, :username)', $data);
	}
}

/*

*/
class TG
{
	/*

	*/
	public function __construct($data = NULL, $token = NULL, $db = NULL)
	{
		/* */
		$this->db = $db;
		
		/* */
		$this->data = $data;
		
		/* */
		$this->token = $token;

		/* */
		$this->keyboard['inline']['reset'] = [
			'inline_keyboard' => [
				[
					[
						'text' => 'Сбросить', 'callback_data' => 'reset'
					]
				]
			]
		];

		$this->keyboard['home'] = [
			'inline_keyboard' => [
				[
					[
						'text' => 'Создать ссылку - Aliexpress', 'callback_data' => 'create.link.aliexpress.0'
					]
				]
			]
		];

		/* */
		if(is_object($this->data))
		{
			$this->account = new Account(
				$this->data->message->chat->id ?? $this->data->callback_query->message->chat->id, $this->db
			);
		}
	}

	/*

	*/
	public function init()
	{
		if(is_object($this->data))
		{
			if(isset($this->data->message->text))
			{
				if(preg_match('/^\/(.*?)$/', $this->data->message->text, $mathes)) 
				{
					/* */
					$this->data->message->text = explode(' ', $mathes[1]);

					/* */
					$method = $this->data->message->text[0];

					/* */
					if(method_exists($this, $method)) 
					{
						return $this->$method();
					}
				}

				if(!empty($account = $this->account->search()))
				{
					if(!empty($account[0]['step']))
					{
						return $this->step($account[0]['step']);
					}
				}
			}
			else
			{
				if(isset($this->data->callback_query))
				{
					return $this->callback();
				}
			}
		}
	}

	/*

	*/
	public function start()
	{
		/* */
		try {
			if(empty($account = $this->account->search())) {
				$this->account->insert([
					'chat' => $this->data->message->chat->id,
					'username' => $this->data->message->chat->username,
				]);

				if(empty($account = $this->account->search())) {
					throw new Exception(true);
				}
			}
		} catch(Exception $e) {
			unset($account);
		}

		/* */
		$message = '👋🏻 Добро пожаловать, ' . $this->data->message->chat->username . PHP_EOL . PHP_EOL;
		$message .= 'Для начала работы нажмите одну из соответствующих кнопок.';

		/* */
		return $this->sendMessage($this->data->message->chat->id, $message, json_encode($this->keyboard['home']));
	}

	/*

	*/
	public function callback()
	{
		switch($this->data->callback_query->data)
		{
			case 'reset':
			{
				/* */
				if(!empty($account = $this->account->search())) {
					$this->account->update('step', '');
					$this->account->update('data', '');
				}

				/* */
				$this->sendMessage($this->data->callback_query->message->chat->id, 'Текущие настройки были сброшены.', json_encode($this->keyboard['home']));

				/* */
				break;
			}
			case 'create.link.aliexpress.0':
			{
				/* */
				$this->account->update('step', 'create.link.aliexpress.1');

				/* */
				$message = 'Введите или вставьте ссылку на товар в формате:' . PHP_EOL . PHP_EOL;
				$message .= 'https://aliexpress.ru/item/10000004786273.html?spm=a2g0r.9294380.productCarousel.2.4a0f7fceUGZlbn&gps-id=6172742';

				/* */
				$this->sendMessage($this->data->callback_query->message->chat->id, $message, json_encode($this->keyboard['inline']['reset']));

				/* */
				break;
			}
		}
	}

	/*

	*/
	public function step($step = NULL)
	{
		switch($step)
		{
			case 'create.link.aliexpress.1':
			{
				/* */
				if(!preg_match('/\/(i|item)\/(.*)\.html/', $this->data->message->text, $redirect)) {
					return $this->sendMessage($this->data->message->chat->id, 'Ссылка на товар указан некорректно.', json_encode($this->keyboard['inline']['reset']));
				}

				/* */
				$redirect = 'https://ru.aliexpress.com/i/' . $redirect[2] . '.html';
				$item = @file_get_contents($redirect);

				/* */
				if(is_bool($item) || !preg_match('/Подробнее\s&\sкупить/', $item)) {
					return $this->sendMessage($this->data->message->chat->id, 'Ссылка на товар указан некорректно.', json_encode($this->keyboard['inline']['reset']));
				}

				/* */
				$data = [ 'redirect' => $redirect ];

				/* */
				$this->account->update('step', 'create.link.aliexpress.2');
				$this->account->update('data', json_encode($data));

				/* */
				$this->sendMessage($this->data->message->chat->id, 'Введите новую стоимость товара: ', json_encode($this->keyboard['inline']['reset']));

				/* */
				break;
			}
			case 'create.link.aliexpress.2':
			{
				/* */
				if(!preg_match('/^[0-9]+$/', $this->data->message->text)) {
					return $this->sendMessage($this->data->message->chat->id, 'Стоимость товара указан некорректно.', json_encode($this->keyboard['inline']['reset']));
				}

				/* */
				$account = $this->account->search();
				$data = json_decode($account[0]['data']);
				$hash = hash('sha256', rand(0, 999999));

				/* */
				try {

					$this->db->prepare('INSERT INTO `items`(`account`, `hash`, `redirect`, `amount`) VALUES (:account, :hash, :redirect, :amount)',
						[
							'account' => $this->data->message->chat->username,
							'hash' => $hash,
							'redirect' => $data->redirect,
							'amount' => $this->data->message->text,
						]
					);

					$this->account->update('step', '');
					$this->account->update('data', '');

				} catch(Exception $e) {
					return $this->sendMessage($this->data->message->chat->id, $e->getMessage(), json_encode($this->keyboard['inline']['reset']));
				}

				/* */
				$protocol = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ? 'https' : 'http';

				/* */
				$message = '✅ *Ссылка успешно создана*' . PHP_EOL . PHP_EOL;
				$message .= '*Оригинальная ссылка на товар:* ' . $data->redirect . PHP_EOL;
				$message .= '*Новая стоимость:* ' . $this->data->message->text . ' рублей.' . PHP_EOL . PHP_EOL;
				$message .= '*Новая ссылка на товар:* ' . ($protocol . '://' . $_SERVER['HTTP_HOST'] . '/item?id=' . $hash);
				
				/* */
				$this->sendMessage($this->data->message->chat->id, $message, json_encode($this->keyboard['home']));

				/* */
				break;
			}
		}
	}

	/*

	*/
	public function sendMessage($chat = NULL, $message = NULL, $keyboard = NULL) {
		return $this->request('sendMessage', 
			[ 
				'chat_id' => $chat, 'text' => $message, 'reply_markup' => $keyboard, 'parse_mode' => 'markdown'
			]
		);
	}

	/*

	*/
	public function request($method = NULL, $params = NULL) {
		return @file_get_contents('https://api.telegram.org/bot' . $this->token . '/' . $method . '?' . http_build_query($params));
	}
}

/*

*/
$telegram = new TG(json_decode(@file_get_contents('php://input')), $_WEB['TELEGRAM']['TOKEN'], $_DB);

/*

*/
if(isset($_GET['webhook'])) {
	var_dump(
		$telegram->request('setwebhook', [
			'url' => $_PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . '/bot'
		])
	);
	die();
}

/*

*/
$telegram->init();