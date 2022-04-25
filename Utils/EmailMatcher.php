<?php declare(strict_types = 1);
/**
 * Yireo SalesBlock2ByEmail for Magento
 *
 * @package     Yireo_SalesBlock2ByEmail
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2ByEmail\Utils;

/**
 * Class EmailMatcher
 * @package Yireo\SalesBlock2ByEmail\Utils
 */
class EmailMatcher
{
    /**
     * @param string $email
     * @param string $matchPattern
     * @return bool
     */
    public function match(string $email, string $matchPattern): bool
    {
        if ($email === $matchPattern) {
            return true;
        }

        if (stristr($email, $matchPattern)) {
            return true;
        }

        return false;
    }
}
