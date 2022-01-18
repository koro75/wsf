<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\StockService;
use Symfony\Component\Console\Input\InputArgument;

/**
 * List fulfillable webshippy orders
 * 
 * Class OrdersFullfillableCommand
 */
class OrdersFullfillableCommand extends OrdersCommand
{
    /**
     * @var string Command name.
     */
    protected static $defaultName = 'webshippy:orders:fulfillable';

    /**
     * @var string Command description
     */
    protected static $defaultDescription = 'List fulfillable webshippy orders';

    /**
     * Load StockService with Symfony dependency injection
     *
     * @required
     */
    public StockService $stockService;


    /**
     * @inheritDoc
     */
    protected function configure() : void
    {
        parent::configure();
        
        $this
            ->addArgument('order', InputArgument::REQUIRED, 'The input stock in JSON format');
    }

    /**
     * Returns order data, filtered by stock
     * 
     * @return array
     */
    protected function collectOrders()
    {
        return $this->orderService->sort()->filterByStock(
            $this->stockService->createStockFromJson($this->input->getArgument('order'))
        )->getOrders();
    }
}