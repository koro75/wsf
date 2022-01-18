<?php
declare(strict_types=1);

namespace App\Model;

class Product
{
    protected int $id;

    public function __construct(int $productId)
    {
        $this->id = $productId;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
