<?php
declare(strict_types=1);

namespace App\Model;

class StockItem
{
    protected Product $product;
    protected int $quantity;
    
    public function __construct(Product $product, $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
