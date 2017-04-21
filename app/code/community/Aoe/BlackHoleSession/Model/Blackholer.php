<?php
class Aoe_BlackHoleSession_Model_Blackholer
{
    protected $isBot;
    protected $isSessionlessRequest;
    protected $config;

    public function isBot()
    {
        if ($this->isBot === null) {
            $this->isBot = false;

            if (!empty($_SERVER['HTTP_USER_AGENT'])) {
                $botRegex = (string) $this->getBlackHoleConfig()->descend('bot_regex');
                if ($botRegex && preg_match($botRegex, $_SERVER['HTTP_USER_AGENT'])) {
                    $this->isBot = true;
                }
            }
        }

        return $this->isBot;
    }

    public function isSessionlessRequest()
    {
        if ($this->isSessionlessRequest === null) {
            $this->isSessionlessRequest = false;

            if (!empty($_SERVER['REQUEST_URI'])) {
                $uriRegex = (string) $this->getBlackHoleConfig()->descend('uri_regex');
                if ($uriRegex && preg_match($uriRegex, $_SERVER['REQUEST_URI'])) {
                    $this->isSessionlessRequest = true;
                }
            }
        }
        return $this->isSessionlessRequest;
    }

    protected function getBlackHoleConfig()
    {
        if ($this->config === null) {
            $this->config = Mage::getConfig()->getNode('global/aoeblackholesession');
        }
        return $this->config;
    }
}
