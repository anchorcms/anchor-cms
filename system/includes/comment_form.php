<?php if(comments_open()): ?>
    <section class="comments">
    
        <h1>Comments</h1>
    
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
        
        <form class="commentform" method="post" action="<?php echo current_url(); ?>">
            <legend>Add your comments</legend>
            
            <?php echo comment_form_notifications(); ?>
            
            <p>
                <label for="name">Your name:</label>
                <?php echo comment_form_input_name(); ?>
            </p>
            
            <p>
                <label for="email">Your email address:</label>
                <?php echo comment_form_input_email(); ?>
                
                <em>Will never be published.</em>
            </p>
            
            <p>
                <label for="text">Your comment:</label>
                <?php echo comment_form_input_text(); ?>
            </p>
            
            <p class="submit">
                <?php echo comment_form_button(); ?>
            </p>
        </form>
    
    </section>
<?php endif; ?>