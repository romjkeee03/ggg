<?php 

if(isset($_POST['i']) && isset($_POST['amount']) && isset($_POST['username'])) {
	/* */
	if(!preg_match('/\/(i|item)\/(.*)\.html/', $_POST['i'], $REDIRECT)) {
		die('Ссылка на товар указан некорректно.');
	}

	/* */
	if(!preg_match('/^[0-9]+$/', $_POST['amount'])) {
		die('Стоимость товара указана некорректно.');
	}

	/* */
	if(!preg_match('/^[a-zA-Z0-9-_.]+$/', $_POST['username'])) {
		die('Username от TELEGRAM указан некорректно.');
	}

	/* */
	$HASH = (hash('md5', rand(0, 999999)) . '-' . hash('md5', rand(0, 999999)));
	$REDIRECT = 'https://ru.aliexpress.com/i/' . $REDIRECT[2] . '.html';
	$ITEM = @file_get_contents($REDIRECT);

	/* */
	if(!is_bool($ITEM) && preg_match('/Подробнее\s&\sкупить/', $ITEM)) {
		/* */
		try {
			$_DB->prepare('INSERT INTO `items`(`account`, `hash`, `redirect`, `amount`) VALUES (:account, :hash, :redirect, :amount)',[
				'account' => $_POST['username'], 'hash' => $HASH, 'redirect' => $REDIRECT, 'amount' => $_POST['amount']
			]);
		}
		catch(Exception $e) {
			die(
				$e->getMessage()
			);
		}

		/* */
		die($_PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . '/item?id=' . $HASH);
	}

	/* */
	die('Произошла ошибка при создании ссылки :(');
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="robots" content="noindex">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Create</title>

	<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

	<style>
		* {
			font-family: 'Montserrat', sans-serif;
			font-size: 14px;
		}
	</style>
</head>
<body>
	<!--  -->
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-4">
				<div class="card card-body my-3">
					<form action="/create" method="POST">
						<div class="form-group">
							<label for="i">Ссылка на товар: </label>
							<input type="text" class="form-control" name="i" id="i">
							<small class="form-text text-muted">Оригинальная ссылка на товар с сайта: Aliexpress.ru. Указывать обязательно.</small>
						</div>
						<div class="form-group">
							<label for="amount">Стоимость товара: </label>
							<input type="number" class="form-control" name="amount" id="amount">
							<small class="form-text text-muted">Новая стоимость товара. Разрешено указывать только цифры. Указывать обязательно.</small>
						</div>
						<div class="form-group">
							<label for="username">Username в TELEGRAM: </label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">@</span>
								</div>
								<input type="text" class="form-control" name="username" id="username">
							</div>
							<small class="form-text text-muted">Ваш username в TELEGRAM. Указывать обязательно.</small>
						</div>
						<button type="submit" class="btn btn-block btn-danger">Создать</button> <div class="info text-center"></div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!--  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	<!--  -->
	<script>
		$(document).ready(function() {
			$('form').submit(function(e) {
				/* */
				e.preventDefault();

				/* */
				$('input[name="i"], input[name="amount"], input[name="username"]').removeClass('is-invalid');
				$('.info').removeClass('pt-3').html('');
				$('button').text('Ожидайте...');

				/* */
				if(!$('input[name="i"]').val().match(/\/(i|item)\/(.*)\.html/)) {
					/* */
					$('button').text('Создать');
					$('input[name="i"]').addClass('is-invalid');
				
					/* */
					return false;
				}

				/* */
				if(!$('input[name="amount"]').val().match(/^[0-9]+$/)) {
					/* */
					$('button').text('Создать');
					$('input[name="amount"]').addClass('is-invalid');
				
					/* */
					return false;
				}

				/* */
				if(!$('input[name="username"]').val().match(/^[a-zA-Z0-9-_.]+$/)) {
					/* */
					$('button').text('Создать');
					$('input[name="username"]').addClass('is-invalid');
				
					/* */
					return false;
				}

				/* */
				$.post(this.action, $(this).serialize()).done(function(data) {
					$('button').text('Создать');
					$('.info').removeClass('pt-3').addClass('pt-3').html('').append('<small>' + data + '</small>');
				});
			});
		});
	</script>
</body>
</html>