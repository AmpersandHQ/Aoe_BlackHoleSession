## Tests

This test suite installs a full version of Magento 1.9.3.1.

The `./tests/travis.sh` script file in this directory will install a working magento in this `.tests/magento` for tests to be run against.

The `phpunit.xml` file in the project root will instantiate magento, and kick off the tests in the `./tests/phpunit` directory 
