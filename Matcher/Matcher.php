<?php
/**
 * Yireo SalesBlock2ByEmail for Magento
 *
 * @package     Yireo_SalesBlock2ByEmail
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types = 1);

namespace Yireo\SalesBlock2ByEmail\Matcher;

use Yireo\SalesBlock2\Api\MatcherInterface;

/**
 * Class Matcher
 * @package Yireo\SalesBlock2ByEmail\Matcher
 */
class Matcher implements MatcherInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 'email';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Email address';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Match by email address';
    }

    /**
     * @return bool
     */
    public function match(): bool
    {
        return false;
    }
}
