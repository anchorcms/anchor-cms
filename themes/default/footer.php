		<div class="wrap">
	            <footer id="bottom">
	                <small>&copy; <?php echo date('Y'); ?> <?php echo site_name(); ?>. All rights reserved.</small>

	                <ul role="navigation">
	                    <li><a href="<?php echo rss_url(); ?>">RSS</a></li>
	                    <?php if(twitter_account()): ?>
	                    <li><a href="<?php echo twitter_url(); ?>">@<?php echo twitter_account(); ?></a></li>
	                    <?php endif; ?>

	                    <li><a href="<?php echo base_url('admin'); ?>" title="Administer your site!">Admin area</a></li>

	                    <li><a href="<?php echo base_url(); ?>" title="Return to my website.">Home</a></li>
	                </ul>
	            </footer>

	        </div>
        </div>
    </body>
</html>
