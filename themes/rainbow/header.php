<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
				
		<title><?php echo page_title(); ?> &mdash; <?php echo site_name(); ?></title>
		<meta name="description" content="<?php echo site_description(); ?>">
		
		<!-- CSS and other pretties -->
		<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700">
		<link rel="stylesheet" href="<?php echo theme_url('css/style.css'); ?>">
		
		<!-- Fix viewport -->
		<meta name="msapplication-window" content="width=device-width;height=device-height">
		<meta name="viewport" content="width=device-width">
		
		<!-- Feeds and pings -->
		<link rel="pingback" href="<?php echo base_url(); ?>/pingback">
		<link rel="alternate" type="application/rss+xml" title="<?php echo site_name(); ?> — Feed" href="<?php echo base_url('feed'); ?>">
		
		<!--[if lt IE 9]>
			<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/r29/html5.min.js"></script>
		<![endif]-->
		
		<!-- SEO related stuffs -->
		<meta name="robots" content="noodp">
		<meta name="generator" content="Anchor CMS 1.0 (beta)">
		
		<!-- About the author -->
		<link rel="canonical" href="<?php echo base_url(); ?>">
		
		<meta property="og:locale" content="en_GB">
		<meta property="og:type" content="article"> <!-- website for homepage, article for single -->
		<meta property="og:title" content="Rainbow Demo — Anchor CMS">
		<meta property="og:description" content="<?php echo page_title(); ?> &mdash; <?php echo site_name(); ?>">
		<meta property="og:url" content="<?php echo base_url(); ?>">
		<meta property="og:site_name" content="<?php echo site_name(); ?>">
		
		<!-- If Facebook page is set -->
		<meta property="article:publisher" content="<?php echo base_url(); ?>">
		<meta property="article:tag" content="robots.txt">
		<meta property="article:section" content="SEO">
		
		<?php if(site_meta('twitter', false)) :?>
		<!-- If twitter username is set -->
		<meta name="twitter:card" content="summary">
		<meta name="twitter:site" content="@<?php echo str_replace('@', '', site_meta('twitter')); ?>">
		<?php endif; ?>
		
		<!-- OpenSearch XML -->
		<link rel="search" type="application/opensearchdescription+xml" href="<?php echo base_url('osd.xml'); ?>" title="<?php echo site_name(); ?>">
	</head>
	
	<body>
		<a href="#page-content" tabindex="1" class="accessible">Skip to content</a>
		
		<div class="page">