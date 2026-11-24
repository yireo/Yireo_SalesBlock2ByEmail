<?php declare(strict_types=1);

namespace Yireo\SalesBlock2ByEmail\Utils;

class EmailNormalizer
{
    private const GMAIL_DOMAINS = ['gmail.com', 'googlemail.com'];

    /**
     * @param string $email
     * @return string
     */
    public function normalizeEmail(string $email): string
    {
        $email = $this->lowercase(trim($email, " \f\n\r\t\v\x00"));
        $position = strrpos($email, '@');
        if ($position === false) {
            return $this->normalizeDomain($email);
        }

        $localPart = substr($email, 0, $position);
        $domain = $this->normalizeDomain(substr($email, $position + 1));

        $plusPosition = strpos($localPart, '+');
        if ($plusPosition !== false) {
            $localPart = substr($localPart, 0, $plusPosition);
        }

        if (in_array($domain, self::GMAIL_DOMAINS, true)) {
            $localPart = str_replace('.', '', $localPart);
            $domain = self::GMAIL_DOMAINS[0];
        }

        return $localPart . '@' . $domain;
    }

    /**
     * @param string $domain
     * @return string
     */
    public function normalizeDomain(string $domain): string
    {
        $domain = rtrim($this->lowercase(trim($domain, " \f\n\r\t\v\x00")), '.');
        if ($domain === '' || !preg_match('/[^\x20-\x7E]/', $domain) || !function_exists('idn_to_ascii')) {
            return $domain;
        }

        $asciiDomain = idn_to_ascii($domain, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
        return is_string($asciiDomain) ? $asciiDomain : $domain;
    }

    /**
     * @param string $email
     * @return string
     */
    public function getDomain(string $email): string
    {
        $position = strrpos($email, '@');
        return $position === false ? '' : substr($email, $position + 1);
    }

    private function lowercase(string $value): string
    {
        return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
    }
}
