<?php
/**
 *    Anchor CMS
 *    Configuration
 *
 *    Since v0.3, the installer has been removed, and you have to
 *    manually add your details, which is a bit more secure.
 *    
 *    To get started, you'll need:
 *     - PHP 5.3+
 *     - a MySQL database with access to add and remove tables
 *     - Basic PHP knowledge
 *     - Fairly intermediate HTML/CSS knowledge.
 *
 *    Also handy (but by no means required):
 *     - A TypeKit subscription
 *     - A Twitter account
 *     - Knowledge of Anchor's custom themes (anchorcms.com/docs/theming)
 */
 
//  Initialise the configuration array
//  You don't need to change this.
return array(

	//  Set up the MySQL database
	//  If this doesn't work, you'll need to contact your host.
	'database' => array(
		
		//  This is localhost 99% of the time (Dreamhost users: this is usually mysql.domain.com)
		'host' => 'localhost',
		
		//  The username
		'username' => 'root',
		
		//  The password
		'password' => '',
		
		//  The database name
		'name' => 'anchorcms'
	),

	//  Set metadata information
	//  This can be accessed from a theme by using $this->get('sitename'), which would grab the sitename, for example.
	'metadata' => array(
		'sitename' => 'My First Anchor Site',
		'description' => 'This is my very cool Anchor CMS site, written in PHP5 and MySQL.',
		
		//  The default theme can accept a Typekit account and use their fonts.
		//  When you get given the script tags to embed, like so:
		//
		//  <script type="text/javascript" src="http://use.typekit.com/pfa5tzi.js"></script>
		//  <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
		//
		//  Extract the random string of text before the ".js", and put it here. (optional, of course)
		'typekit' => '',
		
		//  You can use whatever you want here.
		'twitter' => '',
		'date_format' => 'jS M, Y'
	),

	//  Set the current theme
	//  This is a string name of the folder (i.e: $config['theme'] = 'default').
	'theme' => 'default',

	//  Set debugging options.
	//  Used for development only. I'd turn this off the live site.
	'debug' => false
);
