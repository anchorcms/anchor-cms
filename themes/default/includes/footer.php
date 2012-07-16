<div class="wrap">
        
        	<nav class="categories">
        		<h3>All categories:</h3>
        		
        		<ul>
        		<?php while(categories()): ?>
        			<li>
        				<a href="<?php echo category_url(); ?>" title="<?php echo category_title(); ?>">
        					<?php echo category_title(); ?> <span title="Amount of posts in <?php echo category_title(); ?>">(<?php echo category_count(); ?>)</span>
        				</a>
        			</li>
        		<?php endwhile; ?>
        		</ul>
        
            <footer id="bottom">
                <small>&copy; <?php echo date('Y'); ?> <?php echo site_name(); ?>. All rights reserved.</small>
                    
                <ul role="navigation">
                    <li><a href="<?php echo rss_url(); ?>">RSS</a></li>
                    <?php if(twitter_account()): ?>
                    <li><a href="<?php echo twitter_url(); ?>">@<?php echo twitter_account(); ?></a></li>
                    <?php endif; ?>
                    
                    <li><a href="<?php echo admin_url(); ?>" title="Administer your site!">Admin area</a></li>
                   
                    <li><a href="/" title="Return to my website.">Home</a></li>

                    <?php if(is_debug()): ?><a id="debug_toggle" href="#debug">Show database profile</a><?php endif; ?>
                </ul>
                
                <a id="attribution" title="Powered by Anchor CMS" href="//anchorcms.com">Powered by Anchor CMS</a>
            </footer>

            <?php if(is_debug()) echo db_profile(); ?>
        </div>
    </body>
</html>
