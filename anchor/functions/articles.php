<?php

/*******************************
 * Theme functions for articles
 *******************************/

/**
 * Grab the ID of the current article
 *
 * @return int article ID
 */
function article_id()
{
    return Registry::prop('article', 'id');
}

/**
 * Return the number of the article
 *
 * @return int article number
 * @throws \Exception
 */
function article_number()
{
    return Post::where(Base::table('posts.status'), '=', 'published')
               ->where(Base::table('posts.id'), '<=', article_id())
               ->count();
}

/**
 * Get the article title
 *
 * @return string article title
 */
function article_title()
{
    return Registry::prop('article', 'title');
}

/**
 * Get the article slug
 *
 * @return string article slug
 */
function article_slug()
{
    return Registry::prop('article', 'slug');
}

/**
 * Get the URL to the previous article
 *
 * @param boolean $draft   (optional) whether to include drafted articles
 * @param boolean $archive (optional) whether to include archived articles
 *
 * @return string
 * @throws \Exception
 */
function article_previous_url($draft = false, $archive = false)
{
    return article_adjacent_url('previous', $draft, $archive);
}

/**
 * Get the URL to the next article
 *
 * @param boolean $draft   (optional) whether to include drafted articles
 * @param boolean $archive (optional) whether to include archived articles
 *
 * @return string
 * @throws \Exception
 */
function article_next_url($draft = false, $archive = false)
{
    return article_adjacent_url('next', $draft, $archive);
}

/**
 * Get the URL of the current article
 *
 * @return string current article URL
 */
function article_url()
{
    $page = Registry::get('posts_page');

    return base_url($page->slug . '/' . article_slug());
}

/**
 * Get the article description
 *
 * @return string article description
 */
function article_description()
{
    return Registry::prop('article', 'description');
}

/**
 * Get the HTML for the article
 *
 * @return string article HTML
 */
function article_html()
{
    return Registry::prop('article', 'html');
}

/**
 * Get the markdown for the article
 *
 * @return string article markdown
 */
function article_markdown()
{
    return Registry::prop('article', 'markdown');
}

/**
 * Grab the custom CSS for the article
 *
 * @return string custom article CSS
 */
function article_css()
{
    return Registry::prop('article', 'css');
}

/**
 * Grab the custom JS for the article
 *
 * @return string custom article JS
 */
function article_js()
{
    return Registry::prop('article', 'js');
}

/**
 * Grab the time the article was created
 *
 * @return string article creation timestamp
 */
function article_time()
{
    if ($created = Registry::prop('article', 'created')) {
        return Date::format($created, 'U');
    }
}

/**
 * Grab the date the article was created
 *
 * @return string article creation date
 */
function article_date()
{
    if ($created = Registry::prop('article', 'created')) {
        return Date::format($created);
    }
}

/**
 * Grab the current status of the article
 *
 * @return string article status
 */
function article_status()
{
    return Registry::prop('article', 'status');
}

/**
 * Get the name of the category this article belongs to
 *
 * @return string article category title
 */
function article_category()
{
    if ($category = Registry::prop('article', 'category')) {
        $categories = Registry::get('all_categories');

        return $categories[$category]->title;
    }
}

/**
 * Get the slug of the category this article belongs to
 *
 * @return string article category slug
 */
function article_category_slug()
{
    if ($category = Registry::prop('article', 'category')) {
        $categories = Registry::get('all_categories');

        return $categories[$category]->slug;
    }
}

/**
 * Get the URL of the category this article belongs to
 *
 * @return string article category URL
 */
function article_category_url()
{
    if ($category = Registry::prop('article', 'category')) {
        $categories = Registry::get('all_categories');

        return base_url('category/' . $categories[$category]->slug);
    }
}

/**
 * Get the number of comments for this article
 *
 * @return int number of article comments
 */
function article_total_comments()
{
    return Registry::prop('article', 'total_comments');
}

/**
 * Get the author of this article
 *
 * @return string article author name
 */
function article_author()
{
    return Registry::prop('article', 'author_name');
}

/**
 * Get the ID of the article author
 *
 * @return int article author ID
 */
function article_author_id()
{
    return Registry::prop('article', 'author_id');
}

/**
 * Get the authors bio
 *
 * @return string article author bio
 */
function article_author_bio()
{
    return Registry::prop('article', 'author_bio');
}

/**
 * Get the authors email
 *
 * @return string article author email
 */
function article_author_email()
{
    return Registry::prop('article', 'author_email');
}

/**
 * Get a custom field value
 *
 * @param string   $key     name of the field to retrieve
 * @param string   $default (optional) fallback value
 * @param int|null $id      (optional) field ID
 *
 * @return string custom field value
 * @throws \ErrorException
 * @throws \Exception
 */
function article_custom_field($key, $default = '', $id = null)
{
    if ($id == null) {
        $id = Registry::prop('article', 'id');
    }

    if ($extend = Extend::field('post', $key, $id)) {
        return Extend::value($extend, $default);
    }

    return $default;
}

/**
 * Whether this article is customised
 *
 * @return bool
 */
function customised()
{
    if ($itm = Registry::get('article')) {
        return $itm->js or $itm->css;
    }

    return false;
}

/**
 * Alias for customised()
 *
 * @return bool
 */
function article_customised()
{
    return customised();
}

/**
 * Get the article as an object
 *
 * @return object
 */
function article_object()
{
    return Registry::get('article');
}

/**
 * Get the URL to an adjacent article
 *
 * @param string  $side     (optional) one of prev, previous or next
 * @param boolean $draft    (optional) whether to include drafted articles
 * @param boolean $archived (optional) whether to include archived articles
 *
 * @return string adjacent article URL
 * @throws \Exception
 */
function article_adjacent_url($side = 'next', $draft = false, $archived = false)
{
    $comparison = '>';
    $order      = 'asc';

    if (strtolower($side) == 'prev' || strtolower($side) == 'previous') {
        $comparison = '<';
        $order      = 'desc';
    }

    $page = Registry::get('posts_page');

    /** @var \System\database\query $query */
    $query = Post::where('created', $comparison, Registry::prop('article', 'created'));

    if ( ! $draft) {
        $query = $query->where('status', '!=', 'draft');
    }
    if ( ! $archived) {
        $query = $query->where('status', '!=', 'archived');
    }

    if ($query->count()) {
        $article = $query->sort('created', $order)->fetch();
        $page    = Registry::get('posts_page');

        return base_url($page->slug . '/' . $article->slug);
    }
}

/**
 * Retrieves a list of related articles
 *
 * @param int $n number of articles desired
 *
 * @return array related articles
 * @throws \Exception
 */
function related_posts($n)
{
    $posts   = Post::get(Base::table('posts'), '=', 'published');
    $postArr = [];

    foreach ($posts as $post) :
        if ($post->id != article_id()) {
            if ($post->category == article_category_id()) {
                array_push($postArr, $post);
            }
        }
    endforeach;

    shuffle($postArr);

    $postArr = array_slice($postArr, 0, $n);

    return $postArr;
}

/**
 * Retrieves the article category ID
 *
 * @return string
 */
function article_category_id()
{
    if ($category = Registry::prop('article', 'category')) {
        $categories = Registry::get('all_categories');

        return $categories[$category]->id;
    }
}
