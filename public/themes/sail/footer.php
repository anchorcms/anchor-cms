			<footer>
				<p>&copy; <?php echo date('Y'); ?>. Anchor is proudly open-source software.</p>
				<nav>
					<a href="<?php echo rss_url(); ?>">RSS</a>
					<a href="<?php echo full_url('admin'); ?>">Admin</a>
				</nav>
				<p>{elapsed_time} ms {memory_usage} Mb</p>
			</footer>
		</main>
	</body>
</html>