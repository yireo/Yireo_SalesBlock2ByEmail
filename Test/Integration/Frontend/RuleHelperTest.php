<?php declare(strict_types=1);

namespace Yireo\SalesBlock2ByEmail\Test\Integration\Frontend;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\MutableScopeConfigInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Configuration\Configuration;
use Yireo\SalesBlock2\Helper\Rule;
use Yireo\SalesBlock2\Match\RuleMatch;
use Yireo\SalesBlock2\Matcher\MatcherList;
use Yireo\SalesBlock2\Test\Integration\RuleProvider;
use Yireo\SalesBlock2\Utils\CurrentEmail;
use Yireo\SalesBlock2ByEmail\Matcher\Matcher;

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
        
        $this->assertNotEmpty($customer->getEmail());
        $this->assertTrue($customerSession->isLoggedIn());
        
        $currentEmail = $this->objectManager->get(CurrentEmail::class);
        $this->assertEquals($customer->getEmail(), $currentEmail->getValue());

        $this->setConfigValue('salesblock/settings/enabled', 1);
        
        /** @var Configuration $configuration */
        $configuration = $this->objectManager->get(Configuration::class);
        $this->assertTrue($configuration->enabled());
        
        /** @var MatcherList $matcherList */
        $matcherList = $this->objectManager->get(MatcherList::class);
        $this->assertInstanceOf(Matcher::class, $matcherList->getMatcherByCode('email'));
        
        $this->getRuleProvider()->createRule('email', $customer->getEmail(), true);

        /** @var Rule $ruleHelper */
        $ruleHelper = $this->objectManager->get(Rule::class);
        $rules = $ruleHelper->getRules();
        $this->assertNotEmpty($rules);
        
        foreach ($rules as $rule) {
            foreach ($rule->getConditions() as $condition) {
                $this->assertArrayHasKey('name', $condition);
                $this->assertEquals('email', $condition['name']);
                $this->assertArrayHasKey('value', $condition);
                $this->assertEquals($customer->getEmail(), $condition['value']);
            }
        }

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
