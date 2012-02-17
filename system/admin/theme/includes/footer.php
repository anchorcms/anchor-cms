
	<?php if(($user = Users::authed()) !== false): ?>
	<aside id="sidebar">
		<h2>Status check</h2>
		
		<?php if(error_check() !== false): ?>
		<p>Oh no, we found <?php echo count(error_check()) === 1 ? 'a problem' : 'some problems'; ?>!</p>
		
		<ul>
		    <?php foreach(error_check() as $error): ?>
		    <li><?php echo $error; ?></li>
		    <?php endforeach; ?>
		</ul>
		<?php else: ?>
		    <p>Nice job, keep on going!</p>        
		<?php endif; ?>
	</aside>
	<?php endif; ?>

    <footer id="bottom">
        <small>Powered by Anchor, version <?php echo ANCHOR_VERSION; ?>. 
        <a href="<?php echo Url::make(); ?>">Visit your site</a>.
        <?php if(Config::get('debug', false)): ?>
        <br><a id="debug_toggle" href="#debug">Show database profile</a>
        <?php endif; ?></small>
        
        <em>Make blogging beautiful.</em>
    </footer>

	<?php if(Config::get('debug', false)): ?>
	<?php echo db_profile(); ?>
	<script>
		(function() {
			var g = function(i) {
				var e = document.getElementById(i);
				e.s = function(p, v) {
					this.style[p] = v;
				};
				e.g = function(p) {
					return this.style[p];
				};

				return e;
			};

			var a = g('debug_toggle'), t = g('debug_table');

			var b = function(e) {
				var d = (t.g('display') == '' || t.g('display') == 'none') ? 'block' : 'none';
				t.s('display', d);
				e.preventDefault();
				e.stopPropagation();
			};

			a.addEventListener('click', b, false);
		}());
	</script>
	<?php endif; ?>
	
	</body>
</html>
