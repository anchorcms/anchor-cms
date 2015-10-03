		<div class="wrap">
	            <footer id="bottom">
	                <small>&copy; <?php echo date('Y'); ?> <?php echo site_name(); ?>. Kõik õigused kaitstud.</small>

	                <ul role="navigation">
	                    <li><a href="<?php echo rss_url(); ?>">RSS</a></li>
	                    <?php if(twitter_account()): ?>
	                    <li><a href="<?php echo twitter_url(); ?>">@<?php echo twitter_account(); ?></a></li>
	                    <?php endif; ?>

	                    <li><a href="<?php echo base_url('admin'); ?>" title="Administreeri oma kodulehte!">Admini tsoon</a></li>

	                    <li><a href="<?php echo base_url(); ?>" title="Naase minu kodulehele.">Kodu</a></li>
	                </ul>
	            </footer>

	        </div>
        </div>
    </body>
</html>
