<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login - Free Bulma template</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<!-- Bulma Version 0.7.4-->
	<link rel="stylesheet" href="https://unpkg.com/bulma@0.7.4/css/bulma.min.css" />
	<link rel="stylesheet" type="text/css" href="login.css">
	<style>
		.content {
			text-align: center;
		}
		input {
			width: 30rem;
		}
		pre {
			text-align: left;
			display: inline-block;
			max-width: 50rem;
			line-height: 1em;
		}
	</style>
</head>

<body>
	<div class="content">
		<h1>Crosses👿</h1>
		<?php if (isset($_GET['name'])) { ?>
				こんにちは、<img width="50" height="50" src="<?= preg_replace("/[<>]/", "", $_GET['name']) ?>">さん！
		<?php } else { ?>
			<p>あなたのアイコン画像のURLを教えてください！</p>
			<p>
				<form>
					<input name="name" placeholder="URL" value="https://hakatashi.com/favicon.png" type="url">
					<button type="submit">教える</button>
				</form>
			</p>

			<pre>
				<?php highlight_string(file_get_contents(basename(__FILE__))); ?>
			</pre>
		<?php } ?>
	</div>
</body>

</html>