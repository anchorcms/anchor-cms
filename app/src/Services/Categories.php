<?php

namespace Anchorcms\Services;

use Anchorcms\Mappers\MapperInterface;

class Categories
{
    protected $categories;

    protected $posts;

    public function __construct(MapperInterface $categories, MapperInterface $posts)
    {
        $this->categories = $categories;
        $this->posts = $posts;
    }

    public function allWithPostCounts()
    {
        $query = $this->posts->query()->select('category, COUNT(*) AS post_count')
            ->where('status = :status')
            ->setParameter('status', 'published')
            ->groupBy('category');
        $postCounts = $this->posts->getDb()->fetchAll($query->getSQL(), $query->getParameters());

        return $this->categories->all($postCounts);
    }
}
