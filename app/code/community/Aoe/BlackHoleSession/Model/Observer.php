<?php
class Aoe_BlackHoleSession_Model_Observer
{
    public function stripCookiesFromResponse(Varien_Event_Observer $observer)
    {
        if (!Mage::registry('aoe_blackholesession_strip_cookies_from_response')) {
            return;
        }

        /** @var Mage_Core_Controller_Varien_Front $front */
        $front = $observer->getEvent()->getFront();
        $response = $front->getResponse();
        //$response->clearHeaders();


    }
}
