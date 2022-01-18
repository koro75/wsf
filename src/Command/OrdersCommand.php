<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Order;
use App\Service\OrderService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * List webshippy orders
 *
 * Class OrdersCommand
 */
class OrdersCommand extends Command
{
    /**
     * @var string Command name.
     */
    protected static $defaultName = 'webshippy:orders';

    /**
     * @var string Command description
     */
    protected static $defaultDescription = 'List webshippy orders';

    /**
     * Symfony input interface
     * 
     * @var InputInterface
     */
    protected InputInterface $input;

    /**
     * Load OrderService with Symfony dependency injection
     * 
     * @required
     */
    public OrderService $orderService;
    
    
    /**
     * @inheritDoc
     */
    protected function configure() : void
    {
        $this
            ->setHelp(self::$defaultDescription);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        
        try {
            // renders a table with collected orders 
            $this->output(
                $output,
                $this->collectOrders()
            );

            return Command::SUCCESS;
        } catch (\InvalidArgumentException) {
            
            $output->write('Invalid json!');
            return Command::INVALID;
        } catch (\Throwable $e) {

            // todo: log
            return Command::FAILURE;
        }
    }

    /**
     * Returns order data
     * 
     * @return array
     */
    protected function collectOrders()
    {
        return $this->orderService->sort()->getOrders();
    }
    
    /**
     * Renders a table with collected order data.
     * 
     * @param OutputInterface $output
     * @param Order[] $orders
     */
    protected function output(OutputInterface $output, array $orders)
    {
        $table = (new Table($output))
            ->setHeaders([
                'Product',
                'Quantity',
                'Priority',
                'Created at'
            ]);

        foreach ($orders as $order) {
            $table
                ->addRow([
                    $order->getProduct()->getId(),
                    $order->getQuantity(),
                    $order->getTextualPriority(),
                    $order->getCreatedAt()->format('Y-m-d H:i:s'),
                ]);
        }

        $table->render();
    }
}
