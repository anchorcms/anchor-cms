<?php if(comments_open()): ?>
    <section class="comments">
    
        <h1><?php echo total_comments() . pluralise(total_comments(), ' comment'); ?> <a href="#comment" title="Contribute to the discussion!">Add your own</a></h1>
    
        <?php if(has_comments()): ?>
        <ul class="commentlist">
            <?php while(comments()): ?>
            <li class="comment" id="comment-<?php echo comment_id(); ?>">
                <h2><?php echo comment_name(); ?></h2>
                <time><?php echo relative_time(comment_time()); ?></time> 
                
                <div class="content">
                    <?php echo comment_text(); ?>
                </div>
            </li>
            <?php endwhile; ?>
        </ul>
        <?php endif; ?>
        
        <form id="comment" class="commentform" method="post" action="<?php echo current_url(); ?>#comment">
            <legend>Add your comments</legend>
            
            <?php echo comment_form_notifications(); ?>
            
            <p class="name">
                <label for="name">Your name:</label>
                <?php echo comment_form_input_name(); ?>
            </p>
            
            <p class="email">
                <label for="email">Your email address:</label>
                <em>Will never be published.</em>
                <?php echo comment_form_input_email(); ?>                
            </p>
            
            <p class="textarea">
                <label for="text">Your comment:</label>
                <?php echo comment_form_input_text(); ?>
            </p>
            
            <p class="submit">
                <?php echo comment_form_button(); ?>
            </p>
        </form>
    
    </section>
<?php endif; ?>