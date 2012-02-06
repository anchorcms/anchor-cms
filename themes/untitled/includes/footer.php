
		<footer>
			<a class="anchor" href="<?php echo admin_url(); ?>" title="Site Admin">Anchor Cms</a>
		
			<?php if(twitter_account()): ?>
			<a href="<?php echo twitter_url(); ?>" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @<?php echo twitter_account(); ?></a>
			<script>
				(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if(!d.getElementById(id)){
						js = d.createElement(s);
						js.id = id;
						js.src = "//platform.twitter.com/widgets.js";
						fjs.parentNode.insertBefore(js, fjs);
					}
				}(document, "script", "twitter-wjs"));
			</script>
			<?php endif; ?>
		</footer>

    </body>
</html>
