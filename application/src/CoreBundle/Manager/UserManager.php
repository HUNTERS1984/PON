<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\User;
use CoreBundle\Paginator\Pagination;

class UserManager extends AbstractManager
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
     * @param User $user
     *
     * @return User
     */
    public function createUser(User $user)
    {
        $user
            ->setEnabled(true)
            ->setCreatedAt(new \DateTime());
        $this->saveUser($user);
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function saveUser(User $user)
    {
        $user->setUpdatedAt(new \DateTime());
        return $this->save($user);
    }

    /**
     * @param User $user
     *
     * @return boolean
     */
    public function deleteUser(User $user)
    {
        $user
            ->setDeletedAt(new \DateTime())
            ->setEnabled(false);

        return $this->saveUser($user);
    }

    /**
     * List User
     * @param array $params
     *
     * @return array
     */
    public function listUser($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $conditions = [];
        if(isset($params['userName'])) {
            $conditions = [
                'username' => [
                    'type' => 'like',
                    'value' => "%".$params['userName']."%"
                ]
            ];
        }
        
        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' =>  'NULL'
        ];

        $orderBy = ['createdAt' => 'DESC'];

        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);
        return $this->pagination->render($query, $limit, $offset);
    }

}