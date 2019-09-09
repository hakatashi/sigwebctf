<?php
if (!empty($_GET)) {
	$numbers = array_map(function ($number) { return rand() % 43 + 1; }, array_fill(0, 6, 0));

	$matches = 0;
	foreach ($numbers as $index => $number) {
		if (strcmp($_GET["number" . ($index + 1)], (string)$number) == 0) {
			$matches++;
		}
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
		input {
			width: 3rem;
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
	<h1>TOLO6</h1>

	<?php if (!empty($_GET)) { ?>
		<p>
			当選番号は
			<?php foreach ($numbers as $number) { ?>
				<strong><?= $number ?></strong>
			<?php } ?>
			です。
		</p>
		<p>
			<?= $matches ?>個の数字が一致しました。
		</p>
		<?php if ($matches == 6) { ?>
			<p>
				大当たり！一等の景品のフラグです。
				<code><?= $_ENV["FLAG"] ?></code>
			</p>
		<?php } else { ?>
			<p>
				残念！
			</p>
		<?php } ?>
	<?php } ?>

	<p>
		<form method="GET">
			<input name="number1" value="1" type="number" min="1" max="43" />
			<input name="number2" value="1" type="number" min="1" max="43" />
			<input name="number3" value="1" type="number" min="1" max="43" />
			<input name="number4" value="1" type="number" min="1" max="43" />
			<input name="number5" value="1" type="number" min="1" max="43" />
			<input name="number6" value="1" type="number" min="1" max="43" />
			<button type="submit">引く</button>
		</form>
	</p>

	<pre><?php highlight_string(file_get_contents(basename(__FILE__))); ?></pre>

</div>

</body>

</html>
