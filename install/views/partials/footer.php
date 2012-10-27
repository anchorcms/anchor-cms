        <small>
            You’re installing Anchor version <?php echo VERSION; ?>.
            <a href="//twitter.com/anchorcms">Need help?</a>
        </small>
        
        <script>
            var url = window.location.pathname.split('/');
                url = url[url.length - 1];
            
            document.getElementsByClassName(url).className += ' elapsed';
        </script>
	</body>
</html>