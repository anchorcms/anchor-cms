<?php

namespace Anchorcms\Services;

use Anchorcms\Mappers\MapperInterface;

class Posts
{

    public function __construct(MapperInterface $posts, MapperInterface $postmeta, MapperInterface $extend, MapperInterface $users, MapperInterface $categories)
    {
        $this->posts = $posts;
        $this->postmeta = $postmeta;
        $this->extend = $extend;
        $this->users = $users;
        $this->categories = $categories;
    }

    public function getStatuses(): array
    {
        return [
            'published' => 'Published',
            'draft' => 'Draft',
            'archived' => 'Archived',
        ];
    }

    public function getMapper(): MapperInterface
    {
        return $this->posts;
    }

    protected function getKeys(array $posts, string $property = 'id'): array
    {
        return array_map(function ($post) use ($property) {
            return $post->$property;
        }, $posts);
    }

    public function hydrate(array $posts)
    {
        // hydrate category
        $keys = $this->getKeys($posts, 'category');

        $query = $this->categories->query();

        $query->add('where', $query->expr()->in('id', $keys));

        $categories = $this->categories->fetchAll($query);

        // hydrate author
        $keys = $this->getKeys($posts, 'author');

        $query = $this->users->query();

        $query->add('where', $query->expr()->in('id', $keys));

        $users = $this->users->fetchAll($query);

        foreach ($posts as $post) {
            $post->setCategory(array_reduce($categories, function ($carry, $category) use ($post) {
                return $post->category == $category->id ? $category : $carry;
            }));

            $post->setAuthor(array_reduce($users, function ($carry, $user) use ($post) {
                return $post->author == $user->id ? $user : $carry;
            }));
        }

        return $posts;
    }

    public function getPosts(array $params = []): array
    {
        $defaults = [
            'page' => 1,
            'perpage' => 10,
            'status' => 'published',
            'sort' => 'published',
        ];

        $params = array_merge($defaults, $params);

        $offset = ($params['page'] - 1) * $params['perpage'];

        $query = $this->posts->query()
            ->orderBy($params['sort'], 'DESC')
            ->setMaxResults($params['perpage'])
            ->setFirstResult($offset);

        $query->andWhere('status = :status')
            ->setParameter('status', $params['status']);

        if (! empty($params['category'])) {
            $query->andWhere('category = :category')
                ->setParameter('category', $params['category']);
        }

        $posts = $this->posts->fetchAll($query);

        $this->hydrate($posts);

        return $posts;
    }
}
