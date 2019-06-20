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

namespace Yireo\SalesBlock2ByEmail\Utils;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Quote\Api\Data\CartInterface;

/**
 * Class CurrentEmail
 * @package Yireo\SalesBlock2ByEmail\Utils
 */
class CurrentEmail
{
    /**
     * @var string
     */
    private $customerEmail = '';

    /**
     * @var CartInterface
     */
    private $cart;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * CurrentEmail constructor.
     * @param CartInterface $cart
     * @param CustomerSession $customerSession
     */
    public function __construct(
        CartInterface $cart,
        CustomerSession $customerSession
    ) {
        $this->cart = $cart;
        $this->customerSession = $customerSession;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        if ($this->customerEmail) {
            return (string)$this->customerEmail;
        }

        // Load the customer-record
        $customer = $this->customerSession->getCustomer();
        if ($customer->getId() > 0) {
            $customerEmail = $customer->getEmail();
            if (!empty($customerEmail)) {
                $this->customerEmail = (string)$customerEmail;
                return $this->customerEmail;
            }
        }

        // Check the quote
        $billingAddress = $this->cart->getBillingAddress();
        $customerEmail = $billingAddress->getEmail();
        if (!empty($customerEmail)) {
            $this->customerEmail = (string)$customerEmail;
            return $this->customerEmail;
        }

        $this->customerEmail = (string)$customerEmail;
        return $this->customerEmail;
    }
}
