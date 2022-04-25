<?php declare(strict_types=1);
/**
 * Yireo SalesBlock2ByEmail for Magento
 *
 * @package     Yireo_SalesBlock2ByEmail
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2ByEmail\Test\Unit\Utils;

use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2ByEmail\Utils\EmailMatcher as Target;

/**
 * Class EmailMatcherTest
 *
 * @package Yireo\SalesBlock2ByEmail\Test\Unit\Utils
 */
class EmailMatcherTest extends TestCase
{
    /**
     * Test whether basic matching of email addresses works
     *
     * @dataProvider \Yireo\SalesBlock2ByEmail\Test\Unit\DataProvider\EmailPatterns::getData()
     */
    public function testMatch(string $emailValue, string $matchPattern, bool $returnValue)
    {
        $target = $this->getTargetObject();
        $message = sprintf('Comparing "%s" with "%s"', $emailValue, $matchPattern);
        $this->assertEquals($target->match($emailValue, $matchPattern), $returnValue, $message);
    }

    /**
     * @return Target
     */
    protected function getTargetObject(): Target
    {
        return new Target();
    }
}
