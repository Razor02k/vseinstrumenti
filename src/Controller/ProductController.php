<?php

namespace App\Controller;

use App\Entity\Product;
use App\Http\ApiResponse;
use App\Service\ProductService;

class ProductController extends AbstractController {
    /**
     * Обработчик первого запуска
     * @return ApiResponse
     */
    public function runFirst() {
        $repo = $this->em->getRepository(Product::class);
        if ( $repo->isEmptyTable() ) {
            $service = new ProductService( $this->em );
            $service->generate( 20 );

            return ApiResponse::success( [], 201 );
        }

        return ApiResponse::error( 'Таблица товаров уже содержит записи', 409 );
    }

    /**
     * Вывод всех товаров
     * @return ApiResponse
     */
    public function list() {
        $repo = $this->em->getRepository(Product::class);

        return ApiResponse::success( $repo->getAll() );
    }
}