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

class EmailPatterns
{
    /**
     * @return array
     */
    public static function getData(): array
    {
        return [
            'exact address' => ['info@example.org', 'info@example.org', true],
            'exact address, case' => ['Info@Example.ORG', 'info@example.org', true],
            'exact address, whitespace' => [' info@example.org ', 'info@example.org', true],
            'exact address, trailing dot' => ['info@example.org.', 'info@example.org', true],
            'exact address, plus tag' => ['info+shop@example.org', 'info@example.org', true],
            'exact address, gmail dots' => ['j.o.h.n@gmail.com', 'john@gmail.com', true],
            'exact address, googlemail' => ['john@googlemail.com', 'john@gmail.com', true],
            'dots kept outside gmail' => ['j.ohn@example.org', 'john@example.org', false],
            'other address' => ['info@example.org', 'infoo@example.org', false],
            'partial address' => ['info@example.org', 'info@example', false],
            'partial address, other tld' => ['info@example.com', 'info@example', false],
            'domain with @' => ['info@example.org', '@example.org', true],
            'domain without @' => ['info@example.org', 'example.org', true],
            'subdomain' => ['info@mail.example.org', '@example.org', true],
            'domain suffix lookalike' => ['x@myexample.org', 'example.org', false],
            'domain prefix lookalike' => ['x@example.org.evil.tld', 'example.org', false],
            'idn domain' => ['info@bücher.de', 'xn--bcher-kva.de', true],
            'idn pattern' => ['info@xn--bcher-kva.de', '@bücher.de', true],
            'wildcard local part' => ['sales@example.org', '*@example.org', true],
            'wildcard prefix' => ['spam123@example.org', 'spam*@*', true],
            'wildcard no match' => ['info@example.org', 'spam*@*', false],
            'wildcard is not regex' => ['infoXexample.org@x.org', 'info.example*', false],
            'regex anchors are literal' => ['info@example.org', '^info', false],
            'regex anchors are literal (end)' => ['info@example.org', '@example.org$', false],
            'empty email' => ['', '@example.org', false],
            'no email' => ['not-an-email', 'not-an-email', false],
        ];
    }
}
