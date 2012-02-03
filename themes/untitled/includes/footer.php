
		<footer>
			<?php if(user_authed()): ?>
			<a class="anchor" href="/admin" title="Site Admin">Anchor Cms</a>
			<?php else: ?>
			<a class="anchor" href="//anchorcms.com/" title="Anchor Cms">Anchor Cms</a>
			<?php endif; ?>
		
			<a href="https://twitter.com/<?php echo substr(Config::get('metadata.twitter'), 1); ?>" class="twitter-follow-button" data-show-count="false" data-size="large">Follow <?php echo Config::get('metadata.twitter'); ?></a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</footer>

    </body>
</html>
