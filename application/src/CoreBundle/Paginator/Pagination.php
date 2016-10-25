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
        $data = $paginator->getIterator()->getArrayCopy();
        $total = $paginator->count();
        return $this->response($data, $total, $limit, $offset);
    }

    /**
     * @param $data
     * @param $total
     * @param $limit
     * @param $offset
     * @return array
     */
    public function response($data, $total, $limit, $offset)
    {
        $currentPage = ceil($offset/$limit) + 1;
        $totalPage = ceil($total/$limit);
        $pagination = [
            'limit' => $limit,
            'offset' => $offset,
            'item_total' => $total,
            'page_total' => $totalPage,
            'current_page' => $currentPage,
            'first_page' => $this->getFirstPage(),
            'is_first_page' => $this->isFirstPage($currentPage),
            'is_last_page' => $this->isLastPage($currentPage, $total, $limit),
            'last_page' => $this->getLastPage($total, $limit),
            'next_page' => $this->getNextPage($currentPage, $total, $limit),
            'previous_page' => $this->getPreviousPage($currentPage),
            'pages' => $this->getPages(5, $currentPage, $total, $limit)
        ];
        return [
            'data' =>  $data,
            'pagination' => $pagination
        ];
    }

    public function isFirstPage($currentPage)
    {
        return $currentPage == 1;
    }

    public function isLastPage($currentPage, $total, $limit)
    {
        return $currentPage == $this->getLastPage($total, $limit);
    }

    public function getPages($maxPage, $currentPage, $total, $limit)
    {
        $pages = $maxPage;

        $tmpBegin = $currentPage - floor($pages / 2);
        $tmpEnd = $tmpBegin + $pages - 1;

        if ($tmpBegin < $this->getFirstPage()) {
            $tmpEnd += $this->getFirstPage() - $tmpBegin;
            $tmpBegin = $this->getFirstPage();
        }

        if ($tmpEnd > $this->getLastPage($total, $limit)) {
            $tmpBegin -= $tmpEnd - $this->getLastPage($total, $limit);
            $tmpEnd = $this->getLastPage($total, $limit);
        }

        $begin = max($tmpBegin, $this->getFirstPage());
        $end = $tmpEnd;

        return range($begin, $end, 1);
    }

    public function getPreviousPage($currentPage)
    {
        return $currentPage > $this->getFirstPage() ? $currentPage - 1 : $this->getFirstPage();
    }

    public function getNextPage($currentPage, $total, $limit)
    {
        $lastPage = $this->getLastPage($total, $limit);

        return $currentPage < $lastPage ? $currentPage + 1 : $lastPage;
    }

    public function getFirstPage()
    {
        return 1;
    }

    public function getLastPage($total, $limit)
    {
        return $total > 0 ? ceil($total / $limit) : $this->getFirstPage();
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