<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Photo;

class PhotoManager extends AbstractManager
{
    /**
     * @param Photo $photo
     *
     * @return Photo
     */
    public function savePhoto(Photo $photo)
    {
        $photo->setUpdatedAt(new \DateTime());
        return $this->save($photo);
    }

    /**
     * @param Photo $photo
     *
     * @return Photo
     */
    public function createPhoto(Photo $photo)
    {
        $photo->setCreatedAt(new \DateTime());
        return $this->savePhoto($photo);
    }

    /**
     * @param Photo $photo
     *
     * @return boolean
     */
    public function deleteCouponType(Photo $photo)
    {
        $photo
            ->setDeletedAt(new \DateTime());
        return $this->savePhoto($photo);
    }
}