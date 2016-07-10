<?php

namespace Anchorcms\Mappers;

class Pages extends AbstractMapper
{
    protected $primary = 'id';

    protected $name = 'pages';

    public function id($id)
    {
        return $this->fetchByAttribute('id', $id);
    }

    public function slug($slug)
    {
        return $this->fetchByAttribute('slug', $slug);
    }

    public function menu()
    {
        $query = $this->query();

        $query->where('status = :status')
            ->setParameter('status', 'published')
            ->andWhere('show_in_menu = :show_in_menu')
            ->setParameter('show_in_menu', '1')
            ->orderBy('menu_order', 'ASC');

        return $this->fetchAll($query);
    }

    public function filter(array $input)
    {
        if ($input['status']) {
            $this->where('status', '=', $input['status']);
        }

        if ($input['search']) {
            $term = sprintf('%%%s%%', $input['search']);

            $this->whereNested(function ($where) use ($term) {
                $where('title', 'LIKE', $term)->or('content', 'LIKE', $term);
            });
        }

        return $this;
    }

    public function dropdownOptions(array $options = [])
    {
        $options[0] = 'None';

        $query = $this->query()
            ->where('status = :status')
            ->setParameter('status', 'published')
            ->orderBy('title');
        $pages = $this->fetchAll($query);

        foreach ($pages as $page) {
            $options[$page->id] = sprintf('%s (%s)', $page->name, $page->title);
        }

        return $options;
    }
}
