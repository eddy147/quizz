<?php

namespace IndyDutch\QuizBundle\Repository;

class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    public function search($searchString)
    {
        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :search')
            ->setParameter('search', '%'.$searchString.'%')
            ->getQuery()
            ->getResult();
    }

    public function findAll()
    {
        return $this->findBy(array(), array('name' => 'ASC'));
    }

}
