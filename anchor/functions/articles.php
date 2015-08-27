<?php

/*
	Theme functions for articles
*/

/**
 * Grab the id of the current article
 * @return int
 */
function article_id() {
	return Registry::prop('article', 'id');
}

/**
 * Return the number of the article
 * @return int
 */
function article_number() {
	return Post::where(Base::table('posts.status'), '=', 'published')->where(Base::table('posts.id'), '<=', article_id())->count();
}

/**
 * Get the article title
 * @return string
 */
function article_title() {
	return Registry::prop('article', 'title');
}

/**
 * Get the article slug
 * @return string
 */
function article_slug() {
	return Registry::prop('article', 'slug');
}

/**
 * Get the url to the previous article
 * @param boolean
 * @param boolean
 * @return string
 */
function article_previous_url($draft = false, $archive = false) {
	return article_adjacent_url('previous', $draft, $archive);
}

/**
 * Get the url to the next article
 * @param boolean
 * @param boolean
 * @return string
 */
function article_next_url($draft = false, $archive = false) {
	return article_adjacent_url('next', $draft, $archive);
}

/**
 * Get the url of the current article
 * @return string
 */
function article_url() {
	$page = Registry::get('posts_page');

	return base_url($page->slug . '/' . article_slug());
}

/**
 * Get the article description
 * @return string
 */
function article_description() {
	return Registry::prop('article', 'description');
}

/**
 * Get the html for the article
 * @return string
 */
function article_html() {
	return Registry::prop('article', 'html');
}

/**
 * Get the markdown for the article
 * @return string
 */
function article_markdown() {
	return Registry::prop('article', 'html');
}

/**
 * Grab the custom CSS for the article
 * @return string
 */
function article_css() {
	return Registry::prop('article', 'css');
}

/**
* Grab the custom JS for the article
* @return string
*/
function article_js() {
	return Registry::prop('article', 'js');
}

/**
* Grab the time the article was created
* @return string
*/
function article_time() {
	if($created = Registry::prop('article', 'created')) {
		return Date::format($created, 'U');
	}
}

/**
* Grab the date the article was created
* @return string
*/
function article_date() {
	if($created = Registry::prop('article', 'created')) {
		return Date::format($created);
	}
}

/**
* Grab the current status of the article
* @return string
*/
function article_status() {
	return Registry::prop('article', 'status');
}

/**
* Get the name of the category this
* article belongs to
*
* @return string
*/
function article_category() {
	if($category = Registry::prop('article', 'category')) {
		$categories = Registry::get('all_categories');

		return $categories[$category]->title;
	}
}

/**
* Get the slug of the category this
* article belongs to
*
* @return string
*/
function article_category_slug() {
	if($category = Registry::prop('article', 'category')) {
		$categories = Registry::get('all_categories');

		return $categories[$category]->slug;
	}
}

/**
* Get the url of the category this
* article belongs to
*
* @return string
*/
function article_category_url() {
	if($category = Registry::prop('article', 'category')) {
		$categories = Registry::get('all_categories');

		return base_url('category/' . $categories[$category]->slug);
	}
}

/**
 * Get the number of comments for this article
 * @return int
 */
function article_total_comments() {
	return Registry::prop('article', 'total_comments');
}

/**
 * Get the author of this article
 * @return string
 */
function article_author() {
	return Registry::prop('article', 'author_name');
}

/**
 * Get the id of the article author
 * @return int
 */
function article_author_id() {
	return Registry::prop('article', 'author_id');
}

/**
 * Get the authors bio
 * @return string
 */
function article_author_bio() {
	return Registry::prop('article', 'author_bio');
}

/**
 * Get the authors email
 * @return string
 */
function article_author_email() {
	return Registry::prop('article', 'author_email');
}

/**
 * Get a custom field value
 *
 * @param  string
 * @param  string
 * @return string
 */
function article_custom_field($key, $default = '') {
	$id = Registry::prop('article', 'id');

	if($extend = Extend::field('post', $key, $id)) {
		return Extend::value($extend, $default);
	}

	return $default;
}

/**
 * Is this article customised?
 * @return boolean
 */
function customised() {
	if($itm = Registry::get('article')) {
		return $itm->js or $itm->css;
	}

	return false;
}

/**
 * Alias for customised()
 * @return boolean
 */
function article_customised() {
	return customised();
}

/**
 * Get the article as an object
 * @return object
 */
function article_object() {
	return Registry::get('article');
}

/**
* Get the url to an adjacent article
* @param string		prev || previous || next
* @param boolean
* @param boolean
* @return string
*/
function article_adjacent_url($side = 'next', $draft = false, $archived = false) {
	$comparison = '>';
	$order = 'asc';

	if(strtolower($side) == 'prev' || strtolower($side) == 'previous') {
		$comparison = '<';
		$order = 'desc';
	}

	$page = Registry::get('posts_page');
	$query = Post::where('created', $comparison, Registry::prop('article', 'created'));

	if(!$draft) $query = $query->where('status', '!=', 'draft');
	if(!$archived) $query = $query->where('status', '!=', 'archived');

	if($query->count()) {
		$article = $query->sort('created', $order)->fetch();
		$page = Registry::get('posts_page');

		return base_url($page->slug . '/' . $article->slug);
	}
}

/**
* Get the url to an adjacent article
* @param number of articles desired
* @return object array
*/

function related_posts($n) {
    $posts = Post::get(Base::table('posts'), '=', 'published');
    $postarr = array();
    foreach($posts as $post) :
        if($post->id != article_id()) {
            if($post->category == article_category_id()) {
                array_push($postarr, $post);
            }
        }
    endforeach;
    shuffle($postarr);
    $postarr = array_slice($postarr, 0, $n);
    return $postarr;
}

/**
* Get article category ID
* @return string
*/

function article_category_id() {
    if($category = Registry::prop('article', 'category')) {
        $categories = Registry::get('all_categories');
        return $categories[$category]->id;
    }
}
