<?php
class Aoe_BlackHoleSession_Model_Cookie extends Mage_Core_Model_Cookie
{
    public function set($name, $value, $period = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        if (Mage::getSingleton('aoeblackholesession/blackholer')->isStatelessRequest()) {
            return $this;
        }

        return parent::set($name, $value, $period, $path, $domain, $secure, $httponly);
    }

    public function delete($name, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        if (Mage::getSingleton('aoeblackholesession/blackholer')->isStatelessRequest()) {
            return $this;
        }

        return parent::delete($name, $path, $domain, $secure, $httponly);
    }
}
