<?php
declare(strict_types=1);

namespace App\Model;

class Stock
{
    /**
     * @var StockItem[]
     */
    protected array $stockItems;

    /**
     * @return StockItem[]
     */
    public function getStockItems(): array
    {
        return $this->stockItems;
    }

    public function addStockItem(StockItem $stockItem) : self 
    {
        $this->stockItems[]=$stockItem;
        
        return $this;
    }
    
    public function getStockItemByProduct(Product $product) : ?StockItem
    {
        foreach ($this->stockItems as $stockItem) {
            if ($product == $stockItem->getProduct()) {
                return $stockItem;
            }
        }
    }
}
