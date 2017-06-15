<?php $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'); ?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo __('global.upgrade'); ?></title>
		<style>
			body {
				font: 100% "Helvetica Neue", "Open Sans", "DejaVu Sans", "Arial", sans-serif;
				text-align: center;
				background: #444f5f;
				color: #fff;
			}
			body.finished {
				background: #89b92c;
    			color: #2f3744;	
			}
			body.finished.error {
				background: #dd403c;
			}
			div {
				width: 400px;
				position: absolute;
				left: 50%;
				top: 30%;
				margin: -80px 0 0 -200px;
				padding: 30px;
				box-sizing: border-box;
			    border-radius: 5px;
			    background-color: rgba(255,255,255,0.3);
			}
			h1 {
				font-size: 29px;
				line-height: 35px;
				font-weight: 300;
				margin: 30px 0;
			}
			a, .btn {
				display: inline-block;
				padding: 0 22px;
				background: #2F3744;
				color: #96A4BB;
				font-size: 13px;
				line-height: 38px;
				font-weight: bold;
				text-decoration: none;
				border-radius: 5px;
				margin-left: 5px;
				margin-right: 5px;
				border: none;
				cursor: pointer;
			}
			p > small {
				color: rgba(0,0,0,0.4);
			}
			.loadAnchor {
				transform-origin: 50% 18.2%;
    			animation: spinning 1s infinite linear;
			}
			.dancing_robot {
				width: 100% !important;
				margin-top: 30px;
			}
			@keyframes spinning {
			  0%   { transform: rotate(0deg); }
			  100% { transform: rotate(360deg); }
			}
		</style>
	</head>
	<body>
		<div id="start">
			<img src="<?php echo $base; ?>/anchor/views/assets/img/logo.png" alt="Anchor logo">
			<?php $compare = version_compare(VERSION, $version); ?>
			<?php if($compare < 0) : // new release available ?>
				<h1><?php echo __('global.good_news'); ?></h1>
				<p><?php echo __('global.new_version_available'); ?></p>
				<p><small><?php echo VERSION; ?></small><span> &rarr; <?php echo $version; ?></span></p>
            	<a href="#" onclick="sendAjax()"><?php echo __('global.download_now'); ?></a>
				<a href="<?php echo $base; ?>/admin"><?php echo __('global.upgrade_later'); ?></a>
			<?php elseif($compare == 0) : // same version as newest ?>
				<h1><?php echo __('global.good_news'); ?></h1>
				<p><?php echo __('global.up_to_date'); ?></p>
				<p><?php echo VERSION; ?></p>
				<a href="<?php echo $base; ?>/admin"><?php echo __('global.upgrade_finished_thanks'); ?></a>
			<?php elseif($compare > 0) : // we're at least one ahead! ?>
				<h1>Ooooooweeeeee!</h1>
				<p><?php echo __('global.better_version'); ?></p>
				<p><span><?php echo VERSION; ?> &#10567; </span><small><?php echo $version; ?></small></p>
				<a href="<?php echo $base; ?>/admin"><?php echo __('global.upgrade_finished_thanks'); ?></a>
			<?php else : // SOMETHING'S WRONG! ?>
				<p><?php __('global.error_phrase'); ?></p>
				<a href="<?php echo $base; ?>/admin"><?php echo __('global.error_button'); ?></a>
			<?php endif; ?>
		</div>
		<div id="loading" hidden>
			<img class="loadAnchor" src="<?php echo $base; ?>/anchor/views/assets/img/logo.png" alt="Anchor logo">
			<h1><?php echo __('global.updating'); ?></h1>
		</div>
		<div id="finished" hidden>
			<img src="<?php echo $base; ?>/anchor/views/assets/img/logo.png" alt="Anchor logo">
			<h1 class="fin_h1"></h1>
			<a class="fin_goBack" href="<?php echo Uri::to('admin/upgrade/'); ?>">Try again</a>
			<a class="fin_continue" href="<?php echo Uri::to('admin/'); ?>">Nevermind</a>
			<img class="dancing_robot" src="https://i.imgur.com/VKKeQX6.gif" alt="Gangnam Robot!" />
		</div>
		<script type="text/javascript" src="<?php echo $base; ?>/anchor/views/assets/js/zepto.js"></script>
		<script>
			var sendAjax;
			(function($) {
				function addClass(el, clazz) {
					if(!el) return;
					if(el.classList) el.classList.add(clazz);
					else el.className += " " + clazz;
				}
				
				function finished(success) {
					var h1 = success ? "<?php echo __('global.upgrade_good'); ?>" : "<?php echo __('global.upgrade_bad'); ?>";
					document.querySelector(".fin_h1").innerText = h1;
					
					var goBack = document.querySelector(".fin_goBack");
					var cont = document.querySelector(".fin_continue");
					
					if(success) {
						goBack.parentNode.removeChild(goBack);
						cont.innerText = "<?php echo __('global.upgrade_finished_thanks')?>";
					} else {
						var robot = document.querySelector(".dancing_robot");
						robot.parentNode.removeChild(robot);
					}
					
					!success && addClass(document.querySelector("body"), "error");
					setActiveDiv("finished");
				}
				
				function setActiveDiv(div) {
					document.querySelector("#start").hidden = true;
					document.querySelector("#loading").hidden = true;
					document.querySelector("#finished").hidden = true;
					document.querySelector("#" + div).hidden = false;
					
					if(div === "finished") {
						addClass(document.querySelector("body"), "finished")
					}
				}
				
				sendAjax = function() {
					$.ajax({
						url: "<?php echo Uri::to('admin/upgrade/'); ?>",
						type: "POST",
						success: function(data, textStatus, jqXHR) {
							data = JSON.parse(data);
							console.log(data);
							finished(!!data.success);
						},
						error: function(jqXHR, textStatus, errorThrown) {
							// Error executing ajax.
							console.log(errorThrown);
							finished(false);
						}
					});
					
					setActiveDiv("loading");
				};
			}(Zepto));
		</script>
	</body>
</html>
