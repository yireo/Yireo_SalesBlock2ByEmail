<?php declare(strict_types=1);

/**
 * Yireo SalesBlock2ByEmail for Magento
 *
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2ByEmail\Matcher;

use Magento\Framework\Exception\LocalizedException;
use Yireo\SalesBlock2\Api\MatcherInterface;
use Yireo\SalesBlock2\Api\MatcherValidatorInterface;
use Yireo\SalesBlock2\Exception\NoMatchException;
use Yireo\SalesBlock2\Helper\Data;
use Yireo\SalesBlock2\RuleMatch\RuleMatch;
use Yireo\SalesBlock2\Utils\CurrentEmail;
use Yireo\SalesBlock2ByEmail\Utils\EmailMatcher;

class Matcher implements MatcherInterface, MatcherValidatorInterface
{
    private const MAX_PATTERN_LENGTH = 254;
    private const MAX_WILDCARDS = 3;

    /**
     * @var CurrentEmail
     */
    private $currentEmail;

    /**
     * @var EmailMatcher
     */
    private $emailMatcher;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @param CurrentEmail $currentEmail
     * @param EmailMatcher $emailMatcher
     * @param Data $helper
     */
    public function __construct(
        CurrentEmail $currentEmail,
        EmailMatcher $emailMatcher,
        Data $helper
    ) {
        $this->currentEmail = $currentEmail;
        $this->emailMatcher = $emailMatcher;
        $this->helper = $helper;
    }

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
     * @param string $matchString
     * @return RuleMatch
     * @throws NoMatchException
     */
    public function match(string $matchString): RuleMatch
    {
        $currentEmail = trim($this->currentEmail->getValue(), " \f\n\r\t\v\x00");
        if ($currentEmail === '') {
            throw new NoMatchException(__('No match found'));
        }

        foreach ($this->helper->stringToArray($matchString) as $pattern) {
            if (!$this->emailMatcher->match($currentEmail, $pattern)) {
                continue;
            }

            $match = new RuleMatch((string)__('Matched email with %1', $pattern));
            $match->setVariables(['email' => $currentEmail]);
            return $match;
        }

        throw new NoMatchException(__('No match found'));
    }

    /**
     * @param string $value
     * @return void
     * @throws LocalizedException
     */
    public function validate(string $value): void
    {
        $patterns = $this->helper->stringToArray($value);
        if (empty($patterns)) {
            throw new LocalizedException(__('Email pattern can not be empty'));
        }

        foreach ($patterns as $pattern) {
            $this->validatePattern($pattern);
        }
    }

    /**
     * @param string $pattern
     * @return void
     * @throws LocalizedException
     */
    private function validatePattern(string $pattern): void
    {
        if (strlen($pattern) > self::MAX_PATTERN_LENGTH) {
            throw new LocalizedException(__('Email pattern "%1" is too long', $pattern));
        }

        if (!preg_match('/^[^\s\x00-\x1F\x7F^$<>"\'\\\\()\[\]{}?]+$/u', $pattern)) {
            throw new LocalizedException(
                __('Email pattern "%1" contains unsupported characters. Use an address, a domain or *', $pattern)
            );
        }

        if (substr_count($pattern, '@') > 1) {
            throw new LocalizedException(__('Email pattern "%1" contains more than one @', $pattern));
        }

        if (substr_count($pattern, '*') > self::MAX_WILDCARDS) {
            throw new LocalizedException(__('Email pattern "%1" contains too many wildcards', $pattern));
        }

        if (trim($pattern, '*@.') === '') {
            throw new LocalizedException(__('Email pattern "%1" would match every email address', $pattern));
        }
    }
}
