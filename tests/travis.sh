#!/usr/bin/env bash
DIR_TEST="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
DIR_PROJECT=$(realpath $DIR_TEST/../)
DIR_TEST_MAGE="$DIR_TEST/magento"
DIR_TEST_VENDOR="$DIR_TEST/vendor"

#Clean up existing magento directory
rm -rf $DIR_TEST_VENDOR
rm -rf $DIR_TEST_MAGE && mkdir -p $DIR_TEST_MAGE
rm -rf $DIR_TEST/.modman

#DB settings
if [ -z "$DBHOST" ]; then
    DBHOST='localhost'
fi
if [ -z "$DBUSER" ]; then
    DBUSER='root'
fi
if [ -z "$DBPASS" ]; then
    DBPASS=''
fi

#install magento
#will throw an error if the database already exists
cd $DIR_TEST && composer install -o --no-interaction --prefer-dist
$DIR_TEST/vendor/bin/n98-magerun install --root-dir=. --noDownload --useDefaultConfigParams=yes --dbHost="$DBHOST" --dbUser="$DBUSER" --dbPass="$DBPASS" --dbName="aoe_blackholesession_tests" --baseUrl="http://example.com/" --installationFolder="$DIR_TEST_MAGE"

#Install Aoe_BlackHoleSession using modman
cd $DIR_TEST && $DIR_TEST/vendor/bin/modman init ./magento/
cd $DIR_TEST && $DIR_TEST/vendor/bin/modman link $DIR_PROJECT

#Setup scripts and flush caches
cd $DIR_TEST_MAGE && $DIR_TEST/vendor/bin/n98-magerun sys:setup:run
cd $DIR_TEST_MAGE && $DIR_TEST/vendor/bin/n98-magerun cache:flush

