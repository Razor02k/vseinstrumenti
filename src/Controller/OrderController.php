<?php

namespace App\Controller;

use App\Exception\InvalidInputParamException;
use App\Exception\ValidateException;
use App\Http\ApiResponse;
use App\Service\OrderService;

class OrderController extends AbstractController {
    /**
     * Создание заказа
     * @return ApiResponse
     * @throws InvalidInputParamException
     * @throws ValidateException
     */
    public function create() {
        if ( !$products_ids = $this->request->get( 'products' ) ) {
            throw new InvalidInputParamException( 'products' );
        }
        if ( !is_array( $products_ids ) ) {
            throw new ValidateException( 'Список товаров пустой' );
        }
        foreach ( $products_ids as $product_id ) {
            if ( !$product_id || (int)$product_id <= 0 ) {
                throw new ValidateException( 'Список товаров должен состоять из целочисленных идентификаторов, больше 0' );
            }
        }

        $service = new OrderService( $this->em );
        $orderId = $service->createOrder( $products_ids );

        return ApiResponse::success( [ 'orderId' => $orderId ], 201 );
    }

    /**
     * Оплата заказа
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws InvalidInputParamException
     * @throws ValidateException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function pay() {
        if ( !$order_id = $this->request->get( 'order_id' ) ) {
            throw new InvalidInputParamException( 'order_id' );
        }
        if ( !$amount   = $this->request->get( 'amount' ) ) {
            throw new InvalidInputParamException( 'amount' );
        }

        $service = new OrderService( $this->em );
        $service->payOrder( $order_id, $amount );

        return ApiResponse::success();
    }
}