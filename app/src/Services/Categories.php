<?php

namespace Anchorcms\Services;

use Anchorcms\Mappers\MapperInterface;
use Anchorcms\Models\Category;

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
            ->groupBy('category')
        ;
        $postCounts = $this->posts->getDb()->fetchAll($query->getSQL(), $query->getParameters());

        $categories = $this->categories->all($postCounts);

        $filtered = array_filter($categories, function (Category $category) {
            return $category->postCount() > 0;
        });

        return array_values($filtered);
    }
}
