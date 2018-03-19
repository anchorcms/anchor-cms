<?php

use System\database\query;
use System\uri;

/**
 * user class
 */
class user extends Base
{
    public static $table = 'users';

    /**
     * Searches for users
     *
     * @param array $params search parameters
     *
     * @return \stdClass
     * @throws \Exception
     */
    public static function search($params = [])
    {
        $query = static::where('status', '=', 'active');

        foreach ($params as $key => $value) {
            $query->where($key, '=', $value);
        }

        return $query->fetch();
    }

    /**
     * Paginates the user list
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
        $results = $query
            ->take($perpage)
            ->skip(($page - 1) * $perpage)
            ->sort('real_name', 'desc')
            ->get();

        return new Paginator($results, $count, $page, $perpage, Uri::to('admin/users'));
    }
}
