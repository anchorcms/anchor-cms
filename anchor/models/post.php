<?php

use System\config;

/**
 * post class
 */
class post extends Base
{
    public static $table = 'posts';

    /**
     * Retrieves a post by ID
     *
     * @param int $id post ID
     *
     * @return \post
     * @throws \Exception
     */
    public static function id($id)
    {
        return static::get('id', $id);
    }

    /**
     * Retrieves a post
     *
     * @param string     $row post row name to compare in
     * @param string|int $val post value to compare to
     *
     * @return \stdClass
     * @throws \Exception
     */
    private static function get($row, $val)
    {
        return static::left_join(
            Base::table('users'),
            Base::table('users.id'),
            '=',
            Base::table('posts.author')
        )
                     ->where(Base::table('posts.' . $row), '=', $val)
                     ->fetch([
                         Base::table('posts.*'),
                         Base::table('users.id as author_id'),
                         Base::table('users.bio as author_bio'),
                         Base::table('users.email as author_email'),
                         Base::table('users.real_name as author_name')
                     ]);
    }

    /**
     * Retrieves a post by slug
     *
     * @param string $slug post slug
     *
     * @return \stdClass|bool
     * @throws \Exception
     */
    public static function slug($slug)
    {
        $post = static::get('slug', $slug);

        if ( ! empty($post)) {
            $post->total_comments = static::getCommentCount($post);

            return $post;
        }

        return false;
    }

    /**
     * Retrieves the amount of comments for a post
     *
     * @param \post|\stdClass $post post to retrieve the comment amount for
     *
     * @return int number of comments the post received
     * @throws \Exception
     */
    private static function getCommentCount($post)
    {
        return (int)static::left_join(
            Base::table('comments'),
            Base::table('comments.post'),
            '=',
            Base::table('posts.id')
        )
                          ->where(Base::table('posts.id'), '=', $post->id)
                          ->count();
    }

    /**
     * Retrieves a paginated list of posts
     *
     * @param \category|null $category (optional) category to retrieve posts from
     * @param int            $page     (optional) page offset
     * @param int            $per_page (optional) page limit
     *
     * @return array
     * @throws \Exception
     */
    public static function listing($category = null, $page = 1, $per_page = 10)
    {
        // get total
        /** @var \System\database\query $query */
        $query = static::left_join(
            Base::table('users'),
            Base::table('users.id'),
            '=',
            Base::table('posts.author')
        )
                       ->where(Base::table('posts.status'), '=', 'published');

        if ($category) {
            $query->where(Base::table('posts.category'), '=', $category->id);
        }

        $total = $query->count();

        // get posts
        $posts = $query->sort(Base::table('posts.created'), 'desc')
                       ->take($per_page)
                       ->skip(--$page * $per_page)
                       ->get([
                           Base::table('posts.*'),
                           Base::table('users.id as author_id'),
                           Base::table('users.bio as author_bio'),
                           Base::table('users.real_name as author_name')
                       ]);

        return [$total, $posts];
    }

    /**
     * Searches for posts
     *
     * @param string $term     term to search for
     * @param int    $page     (optional) page offset
     * @param int    $per_page (optional) page limit
     *
     * @return array
     * @throws \Exception
     */
    public static function search($term, $page = 1, $per_page = 10)
    {
        /** @var \System\database\query $query */
        $query = static::left_join(
            Base::table('users'),
            Base::table('users.id'),
            '=',
            Base::table('posts.author')
        )
                       ->where(Base::table('posts.status'), '=', 'published')
                       ->where(Base::table('posts.title'), 'like', '%' . $term . '%')
                       ->or_where(Base::table('posts.html'), 'like', '%' . $term . '%');

        $total = $query->count();

        $posts = $query->take($per_page)
                       ->skip(--$page * $per_page)
                       ->get([
                           Base::table('posts.*'),
                           Base::table('users.id as author_id'),
                           Base::table('users.bio as author_bio'),
                           Base::table('users.real_name as author_name')
                       ]);

        foreach ($posts as $key => $post) {
            if ($post->data['status'] !== 'published') {
                unset($posts[$key]);
            }
        }
        if (count($posts) < 1) {
            $total = 0;
        }

        return [$total, $posts];
    }

    /**
     * Retrieves the amount of posts to show per page
     *
     * @return int
     */
    public static function perPage()
    {
        return (Config::meta('show_all_posts')
            ? self::count() + 1
            : Config::meta('posts_per_page')
        );
    }
}
