<?php

error_reporting(-1);
ini_set('display_errors', true);

// Make sure this is PHP 5.3 or later
if ( ! defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50500) {
	echo 'PHP 5.5.0 or later is required';
	exit(1);
}

// Set default timezone to UTC
if( ! ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

// check composer is installed
$autoload_file = __DIR__ . '/../vendor/autoload.php';

if(false === is_file($autoload_file)) {
	echo 'Composer not installed';
	exit(1);
}

$composer = require $autoload_file;

function dd() {
	echo '<pre>';
	call_user_func_array('var_dump', func_get_args());
	echo '</pre>';
	exit;
}

function e($str) {
	return htmlspecialchars($str, ENT_COMPAT, 'UTF-8', false);
}

$app = require __DIR__ . '/container.php';


$faker = Faker\Factory::create();

$app['categories']->delete();

foreach(range(1, 10) as $i) {
	$app['categories']->insert([
		'title' => $faker->text(30),
		'slug' => $faker->slug,
		'description' => $faker->text(50),
	]);
}

$app['posts']->delete();

foreach(range(1, 10000) as $i) {
	$content = $faker->realText(1000);
	$date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now')->format('Y-m-d H:i:s');

	$app['posts']->insert([
		'title' => $faker->text(50),
		'slug' => $faker->slug,
		'content' => $content,
		'html' => $app['markdown']->parse($content),
		'created' => $date,
		'modified' => $date,
		'author' => 1,
		'category' => rand(1, 10),
		'status' => 'published',
	]);
}
