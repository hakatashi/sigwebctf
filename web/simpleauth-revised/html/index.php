<div style="text-align: center;">
	<?php
	if (!empty($_GET)) {
		if (strcmp($_GET["hash"], md5(rand())) == 0) {
			echo "<h1>Correct! The flag is {$_ENV["FLAG"]}</h1>";
		} else {
			echo "<h1>Wrong hash :(</h1>";
		}
	}
	?>
	<form method="GET">
		<input name="hash" placeholder="cd76ed5a..." type="text">
		<button type="submit">Submit</button>
	</form>
</div>