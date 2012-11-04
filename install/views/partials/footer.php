		<small>
			You’re installing Anchor version <?php echo VERSION; ?>.
			<a href="//twitter.com/anchorcms">Need help?</a>
		</small>

		<script>
			var url = window.location.pathname.split('/');
				url = url[url.length - 1];

			var items = document.getElementsByClassName(url);

			if(url == 'complete') {
				document.body.parentNode.className += 'small';
			}

			for(var i = 0; i < items.length; i++) {
				items[i].className += ' elapsed';
			}
		</script>
	</body>
</html>