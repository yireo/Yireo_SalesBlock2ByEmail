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

namespace Yireo\SalesBlock2ByEmail\Matcher;

use Yireo\SalesBlock2\Api\MatcherInterface;
use Yireo\SalesBlock2\Exception\NoMatchException;
use Yireo\SalesBlock2\Helper\Data;
use Yireo\SalesBlock2\Match\Match;
use Yireo\SalesBlock2ByEmail\Utils\CurrentEmail;
use Yireo\SalesBlock2ByEmail\Utils\EmailMatcher;

/**
 * Class Matcher
 * @package Yireo\SalesBlock2ByEmail\Matcher
 */
class Matcher implements MatcherInterface
{
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
     * Matcher constructor.
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
     * @return Match
     * @throws NoMatchException
     */
    public function match(string $matchString): Match
    {
        $matchStrings = $this->helper->stringToArray($matchString);
        $currentEmail = $this->currentEmail->getValue();

        foreach ($matchStrings as $matchString) {
            if (!$this->emailMatcher->match($currentEmail, $matchString)) {
                continue;
            }

            $message = sprintf('Matched email with %s', $matchString);
            return new Match($message);
        }

        throw new NoMatchException(__('No match found'));
    }
}
