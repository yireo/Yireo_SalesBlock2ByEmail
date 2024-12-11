<?php declare(strict_types=1);

/**
 * Yireo SalesBlock2ByEmail for Magento
 *
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2ByEmail\Matcher;

use Yireo\SalesBlock2\Api\MatcherInterface;
use Yireo\SalesBlock2\Exception\NoMatchException;
use Yireo\SalesBlock2\Helper\Data;
use Yireo\SalesBlock2\RuleMatch\RuleMatch;
use Yireo\SalesBlock2ByEmail\Utils\CurrentEmail;
use Yireo\SalesBlock2ByEmail\Utils\EmailMatcher;

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
     * @return RuleMatch
     * @throws NoMatchException
     */
    public function match(string $matchString): RuleMatch
    {
        $matchStrings = $this->helper->stringToArray($matchString);
        $currentEmail = $this->currentEmail->getValue();

        foreach ($matchStrings as $matchString) {
            if (!$this->emailMatcher->match($currentEmail, $matchString)) {
                continue;
            }

            $message = sprintf('Matched email with %s', $matchString);

            $match = new RuleMatch($message);
            $match->setVariables(['email' => $currentEmail]);
            return $match;
        }

        throw new NoMatchException(__('No match found'));
    }
}
