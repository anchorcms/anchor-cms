        <div class="wrap">
            <footer id="bottom">
                <small>
                    &copy; <?php echo date('Y'); ?> <?php echo site_name(); ?>. All rights reserved.
                    
                    <?php if(user_authed()): ?>
                        <a href="<?php echo admin_url(); ?>" title="Administer your site!">Edit your site</a>.
                    <?php endif; ?>
                </small>
                    
                <ul role="navigation">
                    <li><a href="<?php echo URL_PATH . 'rss'; ?>">RSS</a></li>
                    <li><a href="<?php echo twitter_account(); ?>">Twitter</a></li>
                    
                    <?php if(user_authed()): ?>
                    <li><a href="<?php echo admin_url(); ?>" title="Administer your site!">Admin</a></li>
                    <?php endif; ?>
                </ul>
                
                <a id="attribution" title="Powered by Anchor CMS" href="//anchorcms.com">Powered by Anchor CMS</a>
            </footer>
        </div>
    </body>
</html>
