<?php
class MagenotInstalledForTest extends PHPUnit_Framework_TestCase
{
    public function testAutoloader()
    {
        $this->assertInstanceOf('Mage_Catalog_Model_Product', Mage::getModel('catalog/product'));
    }

    public function testDefaultStoreCode()
    {
        $stores = Mage::app()->getStores();
        $this->assertEquals('default', $stores[1]->getData('code'));
    }
}
