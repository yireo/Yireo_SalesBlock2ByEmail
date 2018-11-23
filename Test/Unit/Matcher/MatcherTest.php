<?php
/**
 * Yireo SalesBlock2ByEmail for Magento
 *
 * @package     Yireo_SalesBlock2ByEmail
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types=1);

namespace Yireo\SalesBlock2ByEmail\Test\Unit\Matcher;

use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Exception\NoMatchException;
use Yireo\SalesBlock2\Helper\Data;
use Yireo\SalesBlock2\Match\Match;
use Yireo\SalesBlock2\Match\MatchHolder;
use Yireo\SalesBlock2ByEmail\Matcher\Matcher as Target;
use Yireo\SalesBlock2ByEmail\Matcher\Matcher;
use Yireo\SalesBlock2ByEmail\Test\Unit\DataProvider\EmailPatterns;
use Yireo\SalesBlock2ByEmail\Utils\CurrentEmail;
use Yireo\SalesBlock2ByEmail\Utils\EmailMatcher;

/**
 * Class MatcherTest
 *
 * @package Yireo\SalesBlock2ByEmail\Test\Unit\Matcher
 */
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
     * @dataProvider \Yireo\SalesBlock2ByEmail\Test\Unit\DataProvider\EmailPatterns::getData()
     * @param string $emailValue
     * @param string $matchPattern
     * @param bool $expectedReturnValue
     * @throws NoMatchException
     */
    public function testMatch(string $emailValue, string $matchPattern, bool $expectedReturnValue)
    {
        $this->currentEmailValue = $emailValue;
        $this->currentMatchPattern = $matchPattern;

        $target = $this->getTargetObject();

        if ($expectedReturnValue === true) {
            $currentValue = $this->getCurrentEmailMock()->getValue();
            $message = sprintf('Comparing "%s" with "%s"', $currentValue, $matchPattern);
            $this->assertInstanceOf(Match::class, $target->match($matchPattern), $message);
        } else {
            $this->expectException(NoMatchException::class);
            $target->match($matchPattern);
        }
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
            CurrentEmail::class,
            [],
            [],
            '',
            false,
            false
        );

        $currentEmail->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue($this->currentEmailValue)
            );

        return $currentEmail;
    }

    /**
     * @return Data
     */
    protected function getHelperMock(): Data
    {
        $helper = $this->createMock(
            Data::class,
            [],
            [],
            '',
            false,
            false
        );

        $helper
            ->method('stringToArray')
            ->will($this->returnValue([$this->currentMatchPattern]));

        return $helper;
    }
}