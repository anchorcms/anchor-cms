<?php echo RSS::headers(); ?>
<rss version="2.0">
    <channel> 
        <title><?php echo htmlentities(site_name()); ?></title>     
        <link>http://<?php echo $_SERVER['HTTP_HOST']; ?></link> 
        <description><?php echo htmlentities(site_description()); ?></description> 
        
        <?php while(posts()): ?>
        <item>
            <link><?php echo 'http://' . $_SERVER['HTTP_HOST'] . post_url(); ?></link>
            <title><?php echo htmlentities(post_title()); ?></title>
            <pubDate><?php echo date(DATE_RSS, post_time()); ?></pubDate>
            <description><?php echo htmlentities(post_description()); ?></description>
        </item>
        <?php endwhile; ?>
    </channel>
</rss>