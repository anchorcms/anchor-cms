<?php theme_include('header'); ?>

	<section class="content wrap">
		<h1>Lehte ei leitud</h1>

		<p>Kahjuks lehte <code>/<?php echo htmlspecialchars(current_url()); ?></code> ei leitud. Sinu parim valik on proovida <a href="<?php echo base_url(); ?>">kodulehte</a>, proovi <a href="#search">otsimist</a>, või mine nuta nurgas (kuigi ma ei soovita seda viimast).</p>
	</section>

<?php theme_include('footer'); ?>
