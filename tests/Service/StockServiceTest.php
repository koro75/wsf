<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Model\StockItem;
use App\Service\StockService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StockServiceTest extends KernelTestCase
{
    /**
     * @var StockService
     */
    protected StockService $testService;

    /**
     * Fixtures for testing
     * 
     * @var array 
     */
    protected array $goodFixtures = [
        '{"1":8,"2":4,"3":5}' => [
            'products' => [1,2,3],
            'quantities' => [8,4,5],
        ],
        '{"1":2,"2":3,"3":1}' => [
            'products' => [1,2,3],
            'quantities' => [2,3,1],
        ],
    ];
    
    /**
     * Fixtures for testing
     * 
     * @var array 
     */
    protected array $badFixtures = [
        '{invalid_json',
        'invalid_json:',
    ];
    
    
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->testService = $container->get(StockService::class);
    }
    
    public function testGoodFixtures()
    {
        foreach ($this->goodFixtures as $fixture => $assertions) {
            $stock = $this->testService->createStockFromJson($fixture);

            $i=0;
            foreach ($stock->getStockItems() as $stockItem) {
                $this->assertInstanceOf('App\Model\StockItem', $stockItem);
                $this->assertInstanceOf('App\Model\Product', $stockItem->getProduct());
                $this->assertIsInt($stockItem->getQuantity());

                $this->assertEquals(
                    $stockItem->getProduct()->getId(),
                    $assertions['products'][$i]
                );
                $i++;
            }
        }
    }

    public function testBadFixtures()
    {
        foreach ($this->badFixtures as $fixture) {

            $this->expectException(\InvalidArgumentException::class);
            $this->testService->createStockFromJson($fixture);
        }
    }
}
