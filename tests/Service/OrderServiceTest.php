<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\OrderService;
use App\Service\StockService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderServiceTest extends KernelTestCase
{
    /**
     * @var OrderService
     */
    protected OrderService $testService;

    /**
     * @var StockService
     */
    protected StockService $stockService;

    /**
     * Fixtures for testing
     *
     * @var array
     */
    protected array $defaultFixtures = [
        '1,2,3,2021-03-25 14:51:47',
        '2,1,2,2021-03-21 14:00:26',
        '2,4,1,2021-03-22 17:41:32',
        '3,1,2,2021-03-22 12:31:54',
        '1,1,1,2021-03-25 19:08:22',
        '3,5,3,2021-03-23 05:01:29',
        '1,6,1,2021-03-21 06:17:20',
        '2,2,1,2021-03-24 11:02:06',
        '3,2,1,2021-03-24 12:39:58',
        '1,8,2,2021-03-22 09:58:09',
    ];
    
    protected array $expectedSortOrder = [
        '3,5,3,2021-03-23 05:01:29',
        '1,2,3,2021-03-25 14:51:47',
        '2,1,2,2021-03-21 14:00:26',
        '1,8,2,2021-03-22 09:58:09',
        '3,1,2,2021-03-22 12:31:54',
        '1,6,1,2021-03-21 06:17:20',
        '2,4,1,2021-03-22 17:41:32',
        '2,2,1,2021-03-24 11:02:06',
        '3,2,1,2021-03-24 12:39:58',
        '1,1,1,2021-03-25 19:08:22',
    ];
    
    protected array $expectedFilteredOrder = [
        '{"1":8,"2":4,"3":5}' => [
            '3,5,3,2021-03-23 05:01:29',
            '1,2,3,2021-03-25 14:51:47',
            '2,1,2,2021-03-21 14:00:26',
            '1,8,2,2021-03-22 09:58:09',
            '3,1,2,2021-03-22 12:31:54',
            '1,6,1,2021-03-21 06:17:20',
            '2,4,1,2021-03-22 17:41:32',
            '2,2,1,2021-03-24 11:02:06',
            '3,2,1,2021-03-24 12:39:58',
            '1,1,1,2021-03-25 19:08:22',
        ],
        '{"1":2,"2":3,"3":1}' => [
            '1,2,3,2021-03-25 14:51:47',
            '2,1,2,2021-03-21 14:00:26',
            '3,1,2,2021-03-22 12:31:54',
            '2,2,1,2021-03-24 11:02:06',
            '1,1,1,2021-03-25 19:08:22',
        ],
    ];

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->testService = $container->get(OrderService::class);
        $this->stockService = $container->get(StockService::class);
    }

    protected function doTestValues(array $orders, array $fixtures)
    {
        $i = 0;
        foreach ($orders as $order) {
            $testArray = str_getcsv($fixtures[$i]);

            $this->assertEquals($order->getProduct()->getId(), $testArray[0]);
            $this->assertEquals($order->getQuantity(), $testArray[1]);
            $this->assertEquals($order->getPriority(), $testArray[2]);
            $this->assertEquals($order->getCreatedAt()->format('Y-m-d H:i:s'), $testArray[3]);

            $i++;
        }
    }

    public function testTypes()
    {
        foreach ($this->testService->getOrders() as $order) {
            $this->assertInstanceOf('App\Model\Order', $order);
            $this->assertInstanceOf('App\Model\Product', $order->getProduct());
            $this->assertInstanceOf('\DateTime', $order->getCreatedAt());
            $this->assertIsInt($order->getQuantity());
        }
    }

    public function testValues()
    {
        $this->doTestValues(
            $this->testService->getOrders(),
            $this->defaultFixtures
        );
    }
    
    public function testSort()
    {
        $this->doTestValues(
            $this->testService->sort()->getOrders(),
            $this->expectedSortOrder
        );
    }
    
    public function testFilter()
    {
        foreach ($this->expectedFilteredOrder as $stockFilter => $expectedValues) {
            
            $this->doTestValues(
                $this->testService->sort()->filterByStock($this->stockService->createStockFromJson($stockFilter))->getOrders(),
                $expectedValues
            );
        }
    }
}