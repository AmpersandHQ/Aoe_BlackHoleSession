<?php
$mageApp = __DIR__ . '/magento/app/Mage.php';

if (!is_file($mageApp)) {
    throw new \Exception("Test suite requires a working magento install, see ./tests/README.md");
}
require_once $mageApp;

Mage::app();
