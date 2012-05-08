<?php
/**
 * These file will show you all the different ways of creating plugins for Anchor.
 * 
 * The first method is simply defining a function and then, telling Anchor about it.
 * Function: Plugins::add_hook($name, $type, $callback);
 * 
 * We're going to keep track of all our hook names so that we can remove them later.
 * Plugins::add_hook returns $name
 */
$hooks = array();
function sayHello() {
	echo 'Hello';
}
$hooks[] = Plugins::add_hook('sayHello', 'before_header', sayHello);

/**
 * We can also use closures
 */
$hooks[] = Plugins::add_hook('sayGoodbye', 'after_footer', function() {
	echo 'Goodbye';
});

/**
 * We can even use functions within classes, like so.
 * Also, some hooks will be given data, and when they are
 * they expect data to be returned.
 */
class someClass {
	public function weLoveCats($post) {
		$post->html = $post->html . " WE LOVE CATS!!!1!";
	}
}
$hooks[] = Plugins::add_hook('weLoveCats', 'retrieve_post_not_in_admin', array('someClass', 'weLoveCats'));

/**
 * Lastly, we can remove hooks using Plugins::remove_hook.
 * In this example, we loop through all hooks and remove them.
 */
foreach ($hooks as $hook) {
	Plugins::remove_hook($hook);
}