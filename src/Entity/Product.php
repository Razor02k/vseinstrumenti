<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products")
 */
class Product implements \JsonSerializable {
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * Цена товара в копейках
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $price;

    /**
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName( string $name ) : self {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice() : int {
        return $this->price;
    }

    /**
     * @param int $price
     * @return self
     */
    public function setPrice( int $price ) : self {
        $this->price = $price;
        return $this;
    }

    public function jsonSerialize() {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'price' => $this->price,
        ];
    }
}