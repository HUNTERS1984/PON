<?php

namespace CoreBundle\Paginator;

use Doctrine\ORM\Tools\Pagination\Paginator;

class Pagination
{
    /**
     * render
     *
     * @param Doctrine\ORM\Query $query
     * @param int $limit
     * @param int $offset
     *
     * @return array
    */
    public function render($query, $limit, $offset)
    {
        // No need to manually get get the result ($query->getResult())
        $currentPage = ceil($offset/$limit) + 1;
        $paginator = $this->paginate($query, $currentPage, $limit);

        $pagination = [
            'limit' => $limit,
            'offset' => $offset,
            'item_total' => $paginator->count(),
            'page_total' => ceil($paginator->count()/$limit),
            'current_page' => $currentPage
        ];

        return [
            'data' =>  $paginator->getIterator()->getArrayCopy(),
            'pagination' => $pagination
        ];
    }

    /**
     * Paginator Helper
     *
     * Pass through a query object, current page & limit
     * the offset is calculated from the page and limit
     * returns an `Paginator` instance, which you can call the following on:
     *
     *     $paginator->getIterator()->count() # Total fetched (ie: `5` posts)
     *     $paginator->count() # Count of ALL posts (ie: `20` posts)
     *     $paginator->getIterator() # ArrayIterator
     *
     * @param Doctrine\ORM\Query $dql   DQL Query Object
     * @param integer            $page  Current page (defaults to 1)
     * @param integer            $limit The total number per page (defaults to 5)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function paginate($dql, $page = 1, $limit = 5)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    /**
     * @param int $pageNumber
     * @param int $limit
     *
     * @return int
     */
    public function getOffsetNumber($pageNumber, $limit)
    {
        return ($pageNumber - 1) * $limit;
    }
}