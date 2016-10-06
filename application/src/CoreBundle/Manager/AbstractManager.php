<?php
namespace CoreBundle\Manager;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Elastica\QueryBuilder\DSL\Query;
use Symfony\Component\Validator\Tests\Fixtures\EntityInterface;

abstract class AbstractManager
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager|\Doctrine\ORM\EntityManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param string $class
     */
    public function __construct(ObjectManager $objectManager, $class)
    {
        $this->objectManager = $objectManager;
        $this->repository    = $objectManager->getRepository($class);
        $metadata            = $objectManager->getClassMetadata($class);
        $this->class         = $metadata->getName();
    }

    /**
     * @param int|string $id
     * @return EntityInterface
     */
    public function findOneById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function find(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * @param $entity
     *
     * @return boolean
    */
    public function delete($entity, $andFlush = true)
    {
        $this->objectManager->remove($entity, $andFlush = true);
        if (true === $andFlush) {
            $this->objectManager->flush();
        }
        return true;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }


    /**
     * @param $entity
     * @param bool $andFlush
     * @return EntityInterface
     */
    protected function save($entity, $andFlush = true)
    {
        $this->objectManager->persist($entity);
        if (true === $andFlush) {
            $this->objectManager->flush();
        }
        return $entity;
    }

    /**
     * Get Query
     * @param array $criticals
     * @param array $orderBys
     * @param int $limit
     * @param int $offset
     *
     * @return Query
     *
    */
    public function getQuery($criticals = [], $orderBys = [], $limit = 10, $offset = 0)
    {
        /** @var QueryBuilder $qb*/
        $qb =  $this->repository
            ->createQueryBuilder('p');

        $index = 0;
        foreach($criticals as $key => $critical) {
            if(in_array($critical['type'], ['is', 'is not'])) {
                $qb->andWhere("p.$key ".$critical['type']." ".$critical['value']);
            }else{
                $qb->andWhere("p.$key ".$critical['type']." ?$index")
                    ->setParameter($index, $critical['value']);
                $index++;
            }
        }


        foreach($orderBys as $key => $orderBy) {
            $qb->addOrderBy("p.$key", $orderBy);
        }

        // Create our query
        return $qb->getQuery();
    }
}