<?php

namespace App\Service;


use App\Entity\Order;
use App\Entity\Product;
use App\Exception\ValidateException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;

class OrderService {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * OrderService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct( EntityManagerInterface $em ) {
        $this->em = $em;

    }

    /**
     * Создаем новый заказ
     * @param array $products_ids
     * @return int
     */
    public function createOrder( array $products_ids ) : int {
        $products = [];
        foreach ( $products_ids as $products_id ) {
            $products[] = $this->em->find( Product::class, $products_id );
        }
        $order = ( new Order() )->setProducts( $products );

        $this->em->persist( $order );
        $this->em->flush();

        return $order->getId();
    }

    /**
     * Оплачиваем заказ
     * @param int $order_id
     * @param int $amount
     * @throws ValidateException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function payOrder( int $order_id, int $amount ) : void {
        $repo = $this->em->getRepository( Order::class );

        /** @var Order|null $order */
        if ( !$order = $repo->findOneBy( [ 'id' => $order_id, 'status' => Order::ORDER_STATUS_NEW ] ) ) {
            throw new ValidateException( 'Заказ не найден, либо уже оплачен' );
        }

        if ( $amount !== $order->getAmount() ) {
            throw new ValidateException( 'Сумма оплаты не совпадает с суммой заказа' );
        }

        $client   = HttpClient::create();
        $response = $client->request( 'GET', 'https://ya.ru' );

        if ( 200 !== $response->getStatusCode() ) {
            throw new ValidateException( 'Ожидаемый статус платежа 200, платежный шлюз вернул: ' . $response->getStatusCode() );
        }

        $order->setStatus( Order::ORDER_STATUS_PAID );
        $this->em->flush();
    }
}