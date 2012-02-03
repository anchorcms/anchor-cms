        <div class="wrap">
            <footer id="bottom">
                <small>
                    &copy; <?php echo date('Y'); ?> <?php echo site_name(); ?>.
                    
                    <?php if(user_authed()): ?>
                        <a href="<?php echo admin_url(); ?>" title="Administer your site!">Edit your site</a>.
                    <?php endif; ?>
                    </small>
                
                <a id="attribution" title="Powered by Anchor CMS" href="//anchorcms.com">Powered by Anchor CMS</a>
            </footer>
        </div>
    </body>
</html>
