<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\Category;
use Symfony\Component\EventDispatcher\Event;

class CategoryEvents extends Event
{

    const PRE_CREATE = 'pon.event.category.pre_create';

    const POST_CREATE = 'pon.event.category.post_create';

    /**
     * @var Category
     */
    protected $category;

    /**
     * @param Category $category
     * @return CategoryEvents
     */
    public function setCategory(Category $category): CategoryEvents
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }
}
