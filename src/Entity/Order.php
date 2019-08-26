<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order {

    public const ORDER_STATUS_NEW  = 'NEW';

    public const ORDER_STATUS_PAID = 'PAID';

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, columnDefinition="ENUM('NEW', 'PAID')")
     */
    private $status;

    /**
     * Сумма заказа в копейках
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Product")
     * @ORM\JoinTable(name="orders_products",
     *      joinColumns={@ORM\JoinColumn(name="order_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     */
    private $products;

    public function __construct() {
        $this->status = self::ORDER_STATUS_NEW;
        $this->amount = 0;
        $this->products = [];
    }

    /**
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus() : string {
        return $this->status;
    }

    /**
     * @param string $status
     * @return self
     */
    public function setStatus( string $status ) : self {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount() : int {
        return $this->amount;
    }

    /**
     * @return array
     */
    public function getProducts() : array {
        return $this->products;
    }

    /**
     * @param Product[] $products
     * @return self
     */
    public function setProducts( array $products ) : self {
        $this->products = $products;

        $this->amount = 0;
        foreach ( $this->products as $product ) {
            $this->amount += $product->getPrice();
        }

        return $this;
    }
}