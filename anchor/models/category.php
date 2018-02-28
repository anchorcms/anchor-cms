<?php

use System\database\query;
use System\uri;

/**
 * category class
 */
class category extends Base
{
    public static $table = 'categories';

    /**
     * Retrieves a list of categories
     *
     * @return array
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function dropdown()
    {
        $items = [];
        $query = Query::table(static::table());

        foreach ($query->sort('title')->get() as $item) {
            $items[$item->id] = $item->title;
        }

        return $items;
    }

    /**
     * Retrieves a category by slug
     *
     * @param string $slug
     *
     * @return \stdClass
     * @throws \Exception
     */
    public static function slug($slug)
    {
        return static::where('slug', 'like', $slug)->fetch();
    }

    /**
     * Paginates category results
     *
     * @param int $page    page offset
     * @param int $perpage page limit
     *
     * @return \Paginator
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function paginate($page = 1, $perpage = 10)
    {
        $query   = Query::table(static::table());
        $count   = $query->count();
        $results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('title')->get();

        return new Paginator($results, $count, $page, $perpage, Uri::to('admin/categories'));
    }
}
