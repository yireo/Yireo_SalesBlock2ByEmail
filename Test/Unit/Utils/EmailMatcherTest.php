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

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2ByEmail\Test\Unit\DataProvider\EmailPatterns;
use Yireo\SalesBlock2ByEmail\Utils\EmailMatcher as Target;

class EmailMatcherTest extends TestCase
{
    #[DataProviderExternal(EmailPatterns::class, 'getData')]
    public function testMatch(string $emailValue, string $matchPattern, bool $returnValue)
    {
        $message = sprintf('Comparing "%s" with "%s"', $emailValue, $matchPattern);
        $this->assertSame($returnValue, (new Target())->match($emailValue, $matchPattern), $message);
    }
}
