<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Order;
use App\Model\Product;
use App\Model\Stock;

class OrderService 
{
    protected const SOURCE = __DIR__ . '/../../data/orders.csv';
    
    /**
     * @var Order[] 
     */
    protected array $orders;

    /**
     * OrderService constructor.
     * 
     * Load the orders from the csv file.
     * 
     * @throws \Exception
     */
    public function __construct()
    {
        try {
            $csv = array_map('str_getcsv', file(self::SOURCE));
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException();
        }
        
        // skip headers
        array_shift($csv);

        // fill orders array with order objects 
        $this->orders = [];
        foreach ($csv as $orderItem) {
            $this->orders[] = (new Order())
                ->setProduct(new Product((int) $orderItem[0]))
                ->setQuantity((int)$orderItem[1])
                ->setPriority((int)$orderItem[2])
                ->setCreatedAt(new \DateTime($orderItem[3]));
        }
    }

    /**
     * @return Order[]
     */
    public function getOrders(): array
    {
        return $this->orders;
    }

    /**
     * Sorts orders by priority and created date. 
     * 
     * @return self
     */
    public function sort() : self
    {
        usort($this->orders, function (Order $a, Order $b) {
            $pc = -1 * ($a->getPriority() <=> $b->getPriority());
            return $pc == 0 ? $a->getCreatedAt() <=> $b->getCreatedAt() : $pc;
        });
        
        return $this;
    }

    /**
     * Filter orders by stock.
     * 
     * @param Stock $stock
     * @return $this
     */
    public function filterByStock(Stock $stock) : self
    {
        foreach ($this->orders as $key => $order) {
            
            $stockItem = $stock->getStockItemByProduct($order->getProduct());

            if ($order->getQuantity() > $stockItem->getQuantity()) {
                unset($this->orders[$key]);
            }
        }
        
        return $this;
    }
}