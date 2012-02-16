        <div class="wrap">

            <?php if(is_debug()): echo db_profile(); endif; ?>

            <footer id="bottom">
                <small>
                    &copy; <?php echo date('Y'); ?> <?php echo site_name(); ?>. All rights reserved.
                    <?php if(is_debug()): ?>
                    <br><em>Anchor took <?php echo execution_time(); ?> Secs to run and used <?php echo memory_usage(); ?>Mib of your memory.</em>
                    <?php endif; ?>
                </small>
                    
                <ul role="navigation">
                    <li><a href="<?php echo rss_url(); ?>">RSS</a></li>
                    <?php if(twitter_account()): ?>
                    <li><a href="<?php echo twitter_url(); ?>">@<?php echo twitter_account(); ?></a></li>
                    <?php endif; ?>
                    
                    <li><a href="<?php echo admin_url(); ?>" title="Administer your site!">Admin area</a></li>
                   
                    <li><a href="/" title="Return to my website.">Home</a></li>
                </ul>
                
                <a id="attribution" title="Powered by Anchor CMS" href="//anchorcms.com">Powered by Anchor CMS</a>
            </footer>
        </div>
    </body>
</html>
