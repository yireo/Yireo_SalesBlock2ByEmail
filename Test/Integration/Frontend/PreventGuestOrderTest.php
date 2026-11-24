<?php declare(strict_types=1);

namespace Yireo\SalesBlock2ByEmail\Test\Integration\Frontend;

use Magento\Framework\App\Config\MutableScopeConfigInterface;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Observer\PreventSaveOrder;
use Yireo\SalesBlock2\Test\Integration\RuleProvider;

class PreventGuestOrderTest extends TestCase
{
    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testGuestOrderWithBlockedEmailIsPrevented(): void
    {
        $this->enableWithRule('@blocked.example');

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Matched email with @blocked.example');
        $this->dispatchOrderSaveBefore('Guest+Shop@Blocked.Example');
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testGuestOrderWithOtherEmailIsAllowed(): void
    {
        $this->enableWithRule('@blocked.example');

        $this->dispatchOrderSaveBefore('guest@notblocked.example');
        $this->addToAssertionCount(1);
    }

    private function enableWithRule(string $pattern): void
    {
        $objectManager = Bootstrap::getObjectManager();
        $objectManager->get(MutableScopeConfigInterface::class)->setValue('salesblock/settings/enabled', 1);
        $objectManager->get(RuleProvider::class)->createRule('email', $pattern, true);
    }

    private function dispatchOrderSaveBefore(string $email): void
    {
        $objectManager = Bootstrap::getObjectManager();
        $order = $objectManager->create(Order::class);
        $order->setCustomerEmail($email);
        $order->setCustomerIsGuest(true);

        $event = new Event(['name' => 'sales_order_save_before', 'order' => $order]);
        $objectManager->get(PreventSaveOrder::class)->execute(new Observer(['event' => $event]));
    }
}
