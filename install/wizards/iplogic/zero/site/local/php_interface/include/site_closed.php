<!DOCTYPE html>
<html>
	<head>
		<title>Работы на сайте</title>
		<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE = edge"><![endif]-->
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.png" />
		<style>
			html, body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
			}
			body {
				background-color: #555;
				background-image: url('<? echo str_replace($_SERVER["DOCUMENT_ROOT"], "", __DIR__); ?>/site_closed/background.jpg');
				position: relative;
				font-family: Arial, Verdana, sans-serif;
				font-size:14px;
				color: #000;
			}
			.back {
				background-color: #fff;
				position: absolute;
				width: 600px;
				height: 600px;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				opacity: 0.9;
				border-radius:15px;
				-moz-border-radius:15px;
				-webkit-border-radius:15px;
			}
			.text {
				position: absolute;
				width: 400px;
				/*height: 600px;*/
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				text-align: center;
			}
			.big {
				font-size: 25px;
				margin-bottom: 30px;
			}
			a, a:hover {
				color: #000;
			}
			img {
				max-width: 100%;
				margin-bottom: 30px;
				border: 0;
			}
			@media screen and (max-width: 600px) {
				.back {
					width:100%;
					top: 350px;
				}
				.text {
					width:80%;
					top: 350px;
				}
			}
		</style>
	</head>
	<body>
		<div class="back"></div>
		<div class="text">
			<img src="<? echo str_replace($_SERVER["DOCUMENT_ROOT"], "", __DIR__); ?>/site_closed/vis.png">
			<img src="<? echo str_replace($_SERVER["DOCUMENT_ROOT"], "", __DIR__); ?>/site_closed/logo.png">
			<p class="big">Сайт временно закрыт.<br>Приносим свои извинения.</p>
			По всем вопросам обращайтесь на адрес:<br><a href="mailto:info@iplogic.ru">info@iplogic.ru</a>
		</div>
	</body>
</html>