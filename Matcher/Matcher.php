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
use Yireo\SalesBlock2\Helper\Data;
use Yireo\SalesBlock2\Match\Match;
use Yireo\SalesBlock2\Match\MatchList;
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
     * @var MatchList
     */
    private $matchList;

    /**
     * @var Data
     */
    private $helper;

    /**
     * Matcher constructor.
     * @param CurrentEmail $currentEmail
     * @param EmailMatcher $emailMatcher
     * @param MatchList $matchList
     * @param Data $helper
     */
    public function __construct(
        CurrentEmail $currentEmail,
        EmailMatcher $emailMatcher,
        MatchList $matchList,
        Data $helper
    ) {
        $this->currentEmail = $currentEmail;
        $this->emailMatcher = $emailMatcher;
        $this->matchList = $matchList;
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
     * @return bool
     */
    public function match(string $matchString): bool
    {
        $matchStrings = $this->helper->stringToArray($matchString);
        foreach ($matchStrings as $matchString) {
            if ($this->emailMatcher->match($this->currentEmail->getValue(), $matchString)) {
                $this->addMatch(sprintf('Matched email with %s', $matchString));
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $message
     */
    private function addMatch(string $message)
    {
        $match = new Match($message);
        $this->matchList->addMatch($match);
    }
}
