<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Post;
use CoreBundle\Paginator\Pagination;

class PostManager extends AbstractManager
{
    /**
     * @var Pagination
    */
    protected $pagination;

    /**
     * @var Pagination $pagination
    */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @param Post $post
     *
     * @return Post
     */
    public function createPost(Post $post)
    {
        $post->setCreatedAt(new \DateTime());
        $this->savePost($post);
    }

    /**
     * @param Post $post
     *
     * @return Post
     */
    public function savePost(Post $post)
    {
        $post->setUpdatedAt(new \DateTime());
        return $this->save($post);
    }

    /**
     * @param Post $post
     *
     * @return boolean
     */
    public function deletePost(Post $post)
    {
        $post
            ->setDeletedAt(new \DateTime());
        return $this->savePost($post);
    }

    /**
     * List Post
     * @param array $params
     *
     * @return array
     */
    public function listPost($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $conditions = [];
       
        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' =>  'NULL'
        ];

        $orderBy = ['createdAt' => 'DESC'];

        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);

        return $this->pagination->render($query, $limit, $offset);
    }

}