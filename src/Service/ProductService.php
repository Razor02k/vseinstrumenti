<?php

namespace App\Service;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ProductService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct( EntityManagerInterface $em ) {
        $this->em = $em;
    }

    /**
     * Генерируем новые товары
     * @param int $limit
     */
    public function generate( int $limit ) {
        for ( $i = 0; $i < $limit; $i++ ) {
            $product = ( new Product() )
                ->setName( "Product #" . mt_rand( 1, 100 ) )
                ->setPrice( mt_rand( 100, 100000 ) )
            ;
            $this->em->persist( $product );
        }

        $this->em->flush();
    }
}