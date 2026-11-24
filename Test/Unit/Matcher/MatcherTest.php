<?php declare(strict_types=1);
/**
 * Yireo SalesBlock2ByEmail for Magento
 *
 * @package     Yireo_SalesBlock2ByEmail
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2ByEmail\Test\Unit\Matcher;

use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2ByEmail\Test\Unit\DataProvider\EmailPatterns;
use Yireo\SalesBlock2\Exception\NoMatchException;
use Yireo\SalesBlock2\Helper\Data;
use Yireo\SalesBlock2\RuleMatch\RuleMatch;
use Yireo\SalesBlock2ByEmail\Matcher\Matcher as Target;
use Yireo\SalesBlock2\Utils\CurrentEmail;
use Yireo\SalesBlock2ByEmail\Utils\EmailMatcher;

class MatcherTest extends TestCase
{
    /**
     * @var string
     */
    private $currentEmailValue = '';
    
    /**
     * @var string
     */
    private $currentMatchPattern = '';
    
    /**
     * Test the code that is used in the rules conditions
     */
    public function testGetCode()
    {
        $target = $this->getTargetObject();
        $this->assertSame($target->getCode(), 'email');
    }
    
    /**
     * Test whether the name makes sense
     */
    public function testGetName()
    {
        $target = $this->getTargetObject();
        $this->assertNotEmpty($target->getName());
    }
    
    /**
     * Test whether the description makes sense
     */
    public function testGetDescription()
    {
        $target = $this->getTargetObject();
        $this->assertNotEmpty($target->getDescription());
    }
    
    /**
     * Test whether basic matching of email addresses works
     *
     * @param string $emailValue
     * @param string $matchPattern
     * @param bool $expectedReturnValue
     * @throws NoMatchException
     */
    #[DataProviderExternal(EmailPatterns::class, 'getData')]
    public function testMatch(string $emailValue, string $matchPattern, bool $expectedReturnValue)
    {
        $this->currentEmailValue = $emailValue;
        $this->currentMatchPattern = $matchPattern;
        
        $target = $this->getTargetObject();
        
        if ($expectedReturnValue === true) {
            $currentValue = $this->getCurrentEmailMock()->getValue();
            $message = sprintf('Comparing "%s" with "%s"', $currentValue, $matchPattern);
            $this->assertInstanceOf(RuleMatch::class, $target->match($matchPattern), $message);
        } else {
            $this->expectException(NoMatchException::class);
            $target->match($matchPattern);
        }
    }
    
    public function testMatchMessageAndVariables()
    {
        $this->currentEmailValue = "john@example.org";
        $this->currentMatchPattern = '@example.org';

        $match = $this->getTargetObject()->match('@example.org');
        $this->assertStringContainsString('@example.org', $this->getMessage($match));
    }

    #[DataProvider('getInvalidPatterns')]
    public function testValidateRejectsInvalidPatterns(string $pattern)
    {
        $this->currentMatchPattern = $pattern;
        $this->expectException(LocalizedException::class);
        $this->getTargetObject()->validate($pattern);
    }

    public static function getInvalidPatterns(): array
    {
        return [
            ['^info'],
            ['@example.org$'],
            ['<img src=x>'],
            ['"quoted"@example.org'],
            ['foo(bar)'],
            ['a@b@c'],
            ['*a*b*c*d'],
            ['*'],
            ['*@*'],
            [str_repeat('a', 250) . '@example.org'],
        ];
    }

    #[DataProvider('getValidPatterns')]
    public function testValidateAcceptsValidPatterns(string $pattern)
    {
        $this->currentMatchPattern = $pattern;
        $this->getTargetObject()->validate($pattern);
        $this->addToAssertionCount(1);
    }

    public static function getValidPatterns(): array
    {
        return [
            ['john@example.org'],
            ['@example.org'],
            ['example.org'],
            ['*@example.org'],
            ['spam*@*'],
            ['@bücher.de'],
        ];
    }

    public function testValidateRejectsEmptyValue()
    {
        $helper = $this->createMock(Data::class);
        $helper->method('stringToArray')->willReturn([]);
        $target = new Target($this->getCurrentEmailMock(), new EmailMatcher(), $helper);

        $this->expectException(LocalizedException::class);
        $target->validate('');
    }

    private function getMessage(RuleMatch $match): string
    {
        $reflection = new \ReflectionProperty(RuleMatch::class, 'message');
        return (string)$reflection->getValue($match);
    }

    /**
     * @return Target
     */
    protected function getTargetObject(): Target
    {
        $currentEmail = $this->getCurrentEmailMock();
        $helper = $this->getHelperMock();
        $emailMatcher = new EmailMatcher();
        
        $target = new Target($currentEmail, $emailMatcher, $helper);
        
        return $target;
    }
    
    /**
     * @return CurrentEmail
     */
    protected function getCurrentEmailMock(): CurrentEmail
    {
        $currentEmail = $this->createMock(
            CurrentEmail::class
        );
        
        $currentEmail->expects($this->any())
            ->method('getValue')
            ->willReturn($this->currentEmailValue);
        
        return $currentEmail;
    }
    
    /**
     * @return Data
     */
    protected function getHelperMock(): Data
    {
        $helper = $this->createMock(Data::class);
        
        $helper
            ->method('stringToArray')
            ->willReturn([$this->currentMatchPattern]);
        
        return $helper;
    }
}
