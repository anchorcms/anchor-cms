<?php

use System\config;
use System\uri;

/**
 * page class
 *
 * @property bool   $status
 * @property string $name
 * @property string $title
 * @property string $slug
 * @property string $html
 * @property int    $parent
 */
class page extends Base
{
    public static $table = 'pages';

    /**
     * Retrieves a page by slug
     *
     * @param string $slug
     *
     * @return \stdClass
     * @throws \Exception
     */
    public static function slug($slug)
    {
        return static::where('slug', '=', $slug)->fetch();
    }

    /**
     * Creates a list of pages
     *
     * @param array $params search parameters
     *
     * @return array
     */
    public static function dropdown($params = [])
    {
        $items   = [];
        $exclude = [];

        if (isset($params['show_empty_option']) and $params['show_empty_option']) {
            $items[0] = 'None';
        }

        if (isset($params['exclude'])) {
            $exclude = (array)$params['exclude'];
        }

        foreach (static::get() as $page) {
            if (in_array($page->id, $exclude)) {
                continue;
            }

            $items[$page->id] = $page->name;
        }

        return $items;
    }

    /**
     * Retrieves the home page
     *
     * @return \stdClass
     * @throws \Exception
     */
    public static function home()
    {
        return static::find(Config::meta('home_page'));
    }

    /**
     * Retrieves the posts page
     *
     * @return \stdClass
     * @throws \Exception
     */
    public static function posts()
    {
        return static::find(Config::meta('posts_page'));
    }

    /**
     * Searches for pages
     *
     * @param string $term     search term
     * @param int    $pageNum  (optional) page offset
     * @param int    $per_page (optional) page limit
     *
     * @return array
     * @throws \Exception
     */
    public static function search($term, $pageNum = 1, $per_page = 10)
    {
        $query = static::where(Base::table('pages.status'), '=', 'published')
                       ->where(Base::table('pages.name'), 'like', '%' . $term . '%');
        //->or_where(Base::table('pages.content'), 'like', '%' . $term . '%'); // This could cause problems?

        $total = $query->count();
        $pages = $query->take($per_page)
                       ->skip(--$pageNum * $per_page)
                       ->get([Base::table('pages.*')]);

        foreach ($pages as $key => $page) {
            if ($page->data['status'] !== 'published') {
                unset($pages[$key]);
            }
        }

        if (count($pages) < 1) {
            $total = 0;
        }

        return [$total, $pages];
    }

    /**
     * Retrieves the URI for a page
     *
     * @return string
     * @throws \Exception
     */
    public function uri()
    {
        return Uri::to($this->relative_uri());
    }

    /**
     * Retrieves the URI for a page relative to its parent
     *
     * @return string
     * @throws \Exception
     */
    public function relative_uri()
    {
        $segments = [$this->slug];
        $parent   = $this->parent;

        while ($parent) {
            $page       = static::find($parent);
            $segments[] = $page->slug;
            $parent     = $page->parent;
        }

        return implode('/', array_reverse($segments));
    }

    /**
     * Whether the page is marked active
     * TODO: This could be simplified to return the condition
     *
     * @return bool
     */
    public function active()
    {
        if (
            Registry::prop('page', 'slug') == $this->slug ||
            Registry::prop('page', 'parent') == $this->id
        ) {
            return true;
        }
    }

    /**
     * Retrieves children pages for this page
     *
     * @return array
     * @throws \Exception
     */
    public function children()
    {
        $query = static::where(
            Base::table('pages.parent'),
            '=',
            $this->data['id'])->sort(Base::table('pages.title')
        );

        return $query->get([Base::table('pages.*')]);
    }
}
