<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Error</title>

		<style type="text/css">
			body {
				background: #fff;
				font-family: sans-serif;
				color: #3f3f3f;
				padding: 10px;
			}
			h1, h3 {
				margin: 0 0 1em 0;
				padding: 0;
			}
			pre {
				font-size: 14px;
				margin: 0;
				padding: 0;
			}
			.content {
				padding: 10px;
				background: #eee;
				margin-bottom: 10px;
			}
		</style>
	</head>
	<body>
		<h1><?php echo $severity; ?></h1>

		<div class="content">
			<h3>Message:</h3>
			<?php echo $message; ?> in <strong><?php echo basename($file); ?></strong> on line <strong><?php echo $line; ?></strong>.
		</div>

		<div class="content">
			<h3>Stack Trace:</h3>

			<pre><?php echo $trace; ?></pre>
		</div>

		<div class="content">
			<h3>Context:</h3>

			<?php if(count($contexts)): ?>
				<?php foreach ($contexts as $num => $context): ?>
					<pre><?php echo htmlentities($num.' '.$context); ?></pre>
				<?php endforeach; ?>
			<?php else: ?>
				Context unavailable.
			<?php endif; ?>
		</div>
	</body>
</html>
