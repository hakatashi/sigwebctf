<?php
$db = new mysqli('mysql', 'hakatashi', '', 'sqlit');

if (!empty($_POST)) {
	if (!isset($_POST['name']) || !isset($_POST['pass'])) {
		die('Bad Request');
	}

	$name = $_POST['name'];
	$pass = $_POST['pass'];

	$result = $db->query("
		SELECT *
		FROM users
		WHERE name = '$name' AND pass = '$pass'
	");

	if (!$result) {
		die($db->error);
	}

	$user = $result->fetch_assoc();

	if (empty($user)) {
		die('Username or password is wrong.');
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://unpkg.com/bulma@0.7.4/css/bulma.min.css" />
	<style>
		.content {
			text-align: center;
		}
		pre {
			text-align: left;
			display: inline-block;
			max-width: 50rem;
			line-height: 1em;
			width: 100%;
		}
	</style>
</head>

<body>
<div class="content">
	<h1>Screw it</h1>

	<?php if (empty($_POST)) { ?>
		<p>Members Only</p>
		<form method="POST">
			<p>ユーザー名 <input name="name" type="text" /></p>
			<p>パスワード <input name="pass" type="password" /></p>
			<button type="submit">ログイン</button>
		</form>

		<pre><?php highlight_string(file_get_contents(basename(__FILE__))); ?></pre>
	<?php } else { ?>
		おめでとう！ <code><?= $_ENV["FLAG"] ?></code>
	<?php } ?>

</div>
</body>

</html>
