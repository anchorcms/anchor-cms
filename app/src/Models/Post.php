<?php

namespace Anchorcms\Models;

use Anchorcms\Traits\Dates;

class Post extends AbstractModel
{

    use Dates;

    protected $meta;

    protected $author;

    protected $category;

    public function setMeta(array $meta)
    {
        $this->meta = $meta;
    }

    public function getMeta($key, $default = null)
    {
        return array_reduce($this->meta, function ($default, $row) use ($key) {
            return $row->key == $key ? $row->data : $default;
        }, $default);
    }

    public function hasMeta($key)
    {
        return array_reduce($this->meta, function ($default, $row) use ($key) {
            return $row->key == $key ? true : $default;
        }, false);
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function url()
    {
        return sprintf('/%s/%s', $this->category->slug, $this->slug);
    }
}
