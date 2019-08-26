<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ProductRepository extends EntityRepository {

    /**
     * Возвращает TRUE если таблица с товарами пустая
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isEmptyTable() : bool {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery( '
            SELECT 1
            FROM App\Entity\Product p
        ' )->setMaxResults( 1 )->setHydrationMode( Query::HYDRATE_SINGLE_SCALAR )
        ;

        return !$query->getOneOrNullResult();
    }

    /**
     * Возвращает все товары
     * @return array
     */
    public function getAll() : array {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery( '
            SELECT p
            FROM App\Entity\Product p
        ' );

        return $query->execute();
    }
}