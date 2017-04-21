<?php

class Aoe_BlackHoleSession_Model_Session extends Mage_Core_Model_Session
{

    protected $isUserSessionSaveMethod = false;

    public function __construct(array $data)
    {
        $singularity = Mage::getSingleton('aoeblackholesession/blackholer');

        if ($singularity->isBot() || $singularity->isSessionlessRequest()) {
            $this->isUserSessionSaveMethod = true;
        }

        parent::__construct($data);
    }

    public function getSessionSaveMethod()
    {
        if ($this->isUserSessionSaveMethod) {
            return 'user';
        } else {
            return parent::getSessionSaveMethod();
        }
    }

    public function getSessionSavePath()
    {
        if ($this->isUserSessionSaveMethod) {
            /* @var $sessionHanlder Aoe_BlackHoleSession_Model_SessionHandler */
            $sessionHandler = Mage::getModel('aoeblackholesession/sessionHandler');
            return array($sessionHandler, 'setHandler');
        } else {
            return parent::getSessionSavePath();
        }
    }

}
