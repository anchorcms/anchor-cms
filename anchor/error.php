<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Whoops</title>
		<style type="text/css">
			body {
				font-family: "Open Sans", sans-serif;
				padding: 4%;
				color: #444;
			}
			h1, h2, h3  {
				margin-top: 0;
				font-weight: normal;
			}
			h1 {
				color: #a94442;
			}
			.context {
				margin: 1rem 0;
			}
			.context code {
				font-style: "Droid Sans Mono", monospace;
				font-size: 1rem;
				background: #fff;
				display: block;
				overflow: hidden;
				margin: 0;
				padding: 8px 12px;
				white-space: pre;
			}
			.context code:nth-child(even) {
				background: #f3f3f3;
			}
			.context code.highlight {
				background: #d9edf7;
			}
		</style>
	</head>
	<body>
		<h1><?php echo $e->getMessage(); ?></h1>

		<?php foreach($frames as $frame): ?>
		<?php if(isset($frame['file'])): ?>
		<div class="frame">
			<h3><?php echo $frame['file']; ?>: <?php echo $frame['line']; ?></h3>

			<div class="context">
				<?php foreach($app['error']->getContext($frame['file'], $frame['line'], $padding = 4) as $i => $l): ?>
				<?php $num = (($i - $padding + 1) + $frame['line']); ?>
				<code<?php echo $num == $frame['line'] ? ' class="highlight"' : ''; ?>><?php echo $num; ?> <?php echo htmlspecialchars(rtrim($l)); ?></code>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>
		<?php endforeach; ?>
	</body>
</html>