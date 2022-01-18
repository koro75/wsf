<?php
declare(strict_types=1);

namespace App\Model;

class Order
{
    protected const PRIORITY_LOW = 1;
    protected const PRIORITY_MEDIUM = 2;
    protected const PRIORITY_HIGH = 3;
    
    
    protected Product $product;

    protected int $quantity;
    
    protected int $priority;
    
    protected \DateTime $createdAt;

    
    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getTextualPriority(): string
    {
        switch ($this->priority) {
            case self::PRIORITY_LOW:
                return 'low';
            break;
            case self::PRIORITY_MEDIUM:
                return 'medium';
            break;
            case self::PRIORITY_HIGH:
            default:
                return 'high';
            break;
        }
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

}
