<?php declare(strict_types=1);
/**
 * Yireo SalesBlock2ByEmail for Magento
 *
 * @package     Yireo_SalesBlock2ByEmail
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2ByEmail\Test\Unit\DataProvider;

/**
 * Class EmailPatterns
 * @package Yireo\SalesBlock2ByEmail\Test\Unit\DataProvider
 */
class EmailPatterns
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            ['info@example.org', 'info@example.org', true],
            ['info@example.org', 'info@example', true],
            ['info@example.org', 'example.org', true],
            ['info@example.org', 'infoo@example.org', false],
            ['info@example.org', '@example.org$', false],
            ['info@example.org', '^info', false],
        ];
    }
}
