<?php

class Aoe_BlackHoleSession_Model_Session extends Mage_Core_Model_Session
{

    protected $isSessionlessRequest = false;
    protected $config;

    public function __construct(array $data)
    {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $botRegex = (string) $this->getBlackHoleConfig()->descend('bot_regex');
            if (preg_match($botRegex, $_SERVER['HTTP_USER_AGENT'])) {
                $this->isSessionlessRequest = true;
            }
        }

        if (!$this->isSessionlessRequest && !empty($_SERVER['REQUEST_URI'])) {
            $uriRegex = (string) $this->getBlackHoleConfig()->descend('uri_regex');
            if (preg_match($uriRegex, $_SERVER['REQUEST_URI'])) {
                $this->isSessionlessRequest = true;
            }
        }

        parent::__construct($data);
    }

    protected function getBlackHoleConfig()
    {
        if ($this->config === null) {
            $this->config = Mage::getConfig()->getNode('global/aoeblackholesession');
        }
        return $this->config;
    }

    public function getSessionSaveMethod()
    {
        if ($this->isSessionlessRequest) {
            return 'user';
        } else {
            return parent::getSessionSaveMethod();
        }
    }

    public function getSessionSavePath()
    {
        if ($this->isSessionlessRequest) {
            $sessionHandler = Mage::getModel('aoeblackholesession/sessionHandler'); /* @var $sessionHanlder Aoe_BlackHoleSession_Model_SessionHandler */
            return array($sessionHandler, 'setHandler');
        } else {
            return parent::getSessionSavePath();
        }
    }

}
