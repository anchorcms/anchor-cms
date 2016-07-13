<?php

namespace Anchorcms\Services;

use Anchorcms\Mappers\MapperInterface;
use Anchorcms\Models\Page;

class Menu
{
    protected $meta;

    protected $pages;

    public function __construct(MapperInterface $meta, MapperInterface $pages)
    {
        $this->meta = $meta;
        $this->pages = $pages;
    }

    public function get()
    {
        $pages = $this->pages->menu();

        // change the url of home page to / by setting the slug to a empty string
        $homepage = $this->meta->key('home_page');

        foreach(array_keys($pages) as $index) {
            if($pages[$index]->id == $homepage) {
                $attributes = $pages[$index]->toArray();
                $attributes['slug'] = '';
                $pages[$index] = new Page($attributes);
            }
        }

        return $pages;
    }
}
