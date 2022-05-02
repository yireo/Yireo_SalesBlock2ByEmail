#!/bin/bash
composer config minimum-stability dev
composer config prefer-stable false
mkdir -p /tmp/composer-files

echo "Composer cache before: `composer -g config cache-files-dir`"
composer -g config cache-files-dir /tmp/composer-files
echo "Composer cache after: `composer -g config cache-files-dir`"

composer require yireo/magento2-replace-bundled:^4.0 --no-update
