<!--

	Be a champ - use HTML5!

  -->
 
<!DOCTYPE html>
<html lang="en-gb">
<head>
	<!-- Page's title -->
	<title><?php sitename(); ?> &middot; <?php title(); ?></title>

	<!-- Styles -->
	<link rel="stylesheet" href="<?php theme_directory(); ?>/global.css" />
	<?php if(has_css()) { ?>
	<link rel="stylesheet" href="<?php css_link(); ?>" />
	<?php } ?>

	<!-- Scripts -->
	<script src="<?php theme_directory(); ?>/global.js"></script>
	<?php if(has_js()) { ?>
	<script src="<?php js_link(); ?>"></script>
	<?php } ?>
	
	<meta name="description" content="<?php echo (!is_home()) ? get_excerpt() : 'This is a default Anchor CMS theme.'; ?>">

</head>
<body class="<?php if(is_home()) { echo 'home'; } else { echo 'single'; } ?>">