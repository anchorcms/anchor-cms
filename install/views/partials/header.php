<!doctype html>
<html lang="en-gb">
	<head>
		<meta charset="utf-8">
		<title>Installin' Anchor CMS</title>
		<meta name="robots" content="noindex, nofollow">

		<link rel="stylesheet" href="<?php echo asset('views/assets/css/install.css'); ?>">
		<link rel="stylesheet" href="<?php echo asset('views/assets/css/chosen.css'); ?>">
		<link rel="stylesheet" href="<?php echo asset('views/assets/css/font-awesome.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo asset('views/assets/css/flaticon.css'); ?>">
	</head>
	<body>

		<nav>
			<img src="<?php echo asset('views/assets/img/logo.png'); ?>">

			<ul>
				<li class="start database metadata account complete"><i class="flaticon-shape"></i></li>
				<li class="database metadata account complete"><i class="flaticon-connection"></i></li>
				<li class="metadata account complete"><i class="flaticon-monitor"></i></li>
				<li class="account complete"><i class="flaticon-avatar"></i></li>
				<li class="complete"><i class="flaticon-mark"></i></li>
			</ul>
		</nav>

		<script>
			(function(w, d, u) {
				var parts = "<?php echo Uri::current(); ?>".split('/'), url = parts.pop(), li = d.getElementsByClassName(url);
				if(url == 'complete') d.body.parentNode.className += 'small';
				for(var i = 0; i < li.length; i++) li[i].className += ' elapsed';
			}(window, document));
		</script>