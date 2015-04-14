<?php theme_include('header.php'); ?>

<aside class="sidebar">
	<header>
		<img class="avatar" src="<?php echo theme_url('img/anchor.png'); ?>">
		
		<hgroup>
			<h1 class="blog-title"><?php echo site_name(); ?></h1>
			<p class="blog-description"><?php echo site_description(); ?></p>
		</hgroup>
		
		<nav>
			<?php while(menu_items()): ?>
			<a <?php if(menu_active()) echo 'class="active"'; ?> href="<?php echo menu_url(); ?>"><?php echo menu_name(); ?></a>
			<?php endwhile; ?>
		</nav>
		
		<small class="copyright">
			&copy; <?php echo date('Y'); ?>.

			<span class="attribution">Powered by <a href="//anchorcms.com">Anchor</a>.</span>

			<?php if(!user_authed()): ?>
				<span class="float-right"><a href="<?php echo base_url('admin'); ?>">Admin</a></span>
			<?php endif; ?>
		</small>
	</header>
</aside>

<div class="right-aligned">
	<?php echo $body; ?>
</div>

<?php theme_include('footer.php'); ?>