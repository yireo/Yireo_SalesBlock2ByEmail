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

class EmailMatcher
{
    /**
     * @var EmailNormalizer
     */
    private $emailNormalizer;

    /**
     * @param EmailNormalizer|null $emailNormalizer
     */
    public function __construct(
        ?EmailNormalizer $emailNormalizer = null
    ) {
        $this->emailNormalizer = $emailNormalizer ?? new EmailNormalizer();
    }

    /**
     * Match an email address with a pattern:
     * - a full address (`john@example.com`) matches exactly
     * - a domain (`@example.com` or `example.com`) matches that domain and its subdomains
     * - a pattern with `*` is a wildcard match against the full address
     *
     * @param string $email
     * @param string $matchPattern
     * @return bool
     */
    public function match(string $email, string $matchPattern): bool
    {
        $email = $this->emailNormalizer->normalizeEmail($email);
        $matchPattern = trim($matchPattern, " \f\n\r\t\v\x00");
        if ($email === '' || $matchPattern === '' || strpos($email, '@') === false) {
            return false;
        }

        if (strpos($matchPattern, '*') !== false) {
            return $this->matchWildcard($email, $matchPattern);
        }

        if ($matchPattern[0] === '@' || strpos($matchPattern, '@') === false) {
            return $this->matchDomain($email, ltrim($matchPattern, '@'));
        }

        return $email === $this->emailNormalizer->normalizeEmail($matchPattern);
    }

    private function matchDomain(string $email, string $domainPattern): bool
    {
        $domainPattern = $this->emailNormalizer->normalizeDomain($domainPattern);
        if ($domainPattern === '') {
            return false;
        }

        $domain = $this->emailNormalizer->getDomain($email);
        if ($domain === $domainPattern) {
            return true;
        }

        return substr($domain, -strlen('.' . $domainPattern)) === '.' . $domainPattern;
    }

    private function matchWildcard(string $email, string $matchPattern): bool
    {
        $matchPattern = function_exists('mb_strtolower')
            ? mb_strtolower($matchPattern, 'UTF-8')
            : strtolower($matchPattern);

        $regex = '/^' . str_replace('\*', '.*', preg_quote($matchPattern, '/')) . '$/';
        return preg_match($regex, $email) === 1;
    }
}
