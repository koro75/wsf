<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Product;
use App\Model\Stock;
use App\Model\StockItem;

class StockService
{
    /**
     * Create stock from JSON data.
     * 
     * @param string $input
     * @return Stock
     */
    public function createStockFromJson(string $input): Stock
    {
        $stockArray = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException();
        }

        $stock = new Stock();
        foreach ($stockArray as $productId => $quantity) {

            $stock->addStockItem(
                new StockItem(
                    new Product($productId),
                    $quantity
                )
            );
        }

        return $stock;
    }
}