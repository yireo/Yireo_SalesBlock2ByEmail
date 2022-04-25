<?php declare(strict_types=1);

namespace Yireo\SalesBlock2ByEmail\Test\Integration\Frontend;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\MutableScopeConfigInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Helper\Rule;
use Yireo\SalesBlock2\Match\RuleMatch;
use Yireo\SalesBlock2\Test\Integration\RuleProvider;

class RuleHelperTest extends TestCase
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Setup dependencies
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testFindRuleWithAtLeastOneRuleThatMatchesByEmail()
    {
        /** @var Session $customerSession */
        $customerSession = $this->objectManager->get(Session::class);
        $customerSession->loginById(1);
        $customer = $customerSession->getCustomer();

        $this->setConfigValue('salesblock/settings/enabled', 1);
        $this->getRuleProvider()->createRule('email', $customer->getEmail(), true);

        /** @var Rule $ruleHelper */
        $ruleHelper = $this->objectManager->get(Rule::class);
        $rules = $ruleHelper->getRules();
        $this->assertNotEmpty($rules);

        try {
            $match = $ruleHelper->findMatch();
            $this->assertInstanceOf(RuleMatch::class, $match);
        } catch (NotFoundException $e) {
            $this->fail('No match found: ' . $e->getMessage());
        }
    }

    private function getRuleProvider(): RuleProvider
    {
        return $this->objectManager->get(RuleProvider::class);
    }

    private function setConfigValue(string $configPath, $value)
    {
        $this->objectManager->get(MutableScopeConfigInterface::class)->setValue($configPath, $value);
    }
}
