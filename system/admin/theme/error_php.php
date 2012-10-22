<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Error</title>
	</head>
	<body>
		<h1>Unhandled Exception</h1>

		<h3>Message:</h3>
		<?php echo $message; ?> in <strong><?php echo str_replace(PATH, '', $file); ?></strong> on line <strong><?php echo $line; ?></strong>.

		<h3>Stack Trace:</h3>

		<pre><?php echo $trace; ?></pre>
	</body>
</html>