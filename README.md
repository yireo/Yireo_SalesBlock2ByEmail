# Yireo SalesBlock2ByEmail for Magento 2
This module is a helper-module for the [Yireo_SalesBlock2](https://www.yireo.com/software/magento-extensions/salesblock2) extension, that allows you to block orders from being placed, based on specific rules defined in the Magento Admin Panel.

This specific module allows you to match by a specific email address. When a word is added to match an email address, the word is matched case-insensitive and regardless of the position. For instance, the following matches would be found when an order is placed with the email address `info@example.org`:

- `info@`
- `example.org`
- `@example.org`
- `Example`
