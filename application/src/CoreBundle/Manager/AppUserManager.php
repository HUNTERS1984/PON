<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Paginator\Pagination;

class AppUserManager extends AbstractManager
{

    public function dummy(AppUser $user)
    {
        $this->save($user);
    }

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
     * @param AppUser $appUser
     *
     * @return AppUser
     */
    public function createAppUser(AppUser $appUser)
    {
        $appUser
            ->setEnabled(true)
            ->setCreatedAt(new \DateTime());
        $this->saveAppUser($appUser);
    }

    /**
     * @param AppUser $appUser
     *
     * @return AppUser
     */
    public function saveAppUser(AppUser $appUser)
    {
        $appUser->setUpdatedAt(new \DateTime());
        return $this->save($appUser);
    }

    /**
     * @param AppUser $appUser
     *
     * @return boolean
     */
    public function deleteAppUser(AppUser $appUser)
    {
        $appUser
            ->setDeletedAt(new \DateTime())
            ->setEnabled(false);

        return $this->saveAppUser($appUser);
    }

    /**
     * List App User
     * @param array $params
     *
     * @return array
     */
    public function listAppUser($params)
    {
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $offset = isset($params['offset']) ? $params['offset'] : 0;

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