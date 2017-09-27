<?php
class Aoe_BlackHoleSession_Model_Blackholer
{
    protected $isBot;
    protected $isStatelessRequest;

    protected $config;

    public function __construct()
    {
        /**
         * Bots will not receive a real session but will have default cookie values passed down to them
         */
        $this->isBot = false;
        if (isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) {
            $botRegex = (string) $this->getBlackHoleConfig()->descend('bot_regex');
            if ($botRegex && preg_match($botRegex, $_SERVER['HTTP_USER_AGENT'])) {
                $this->isBot = true;
            }
        }

        /**
         * If a session is instantiated reinforce the FLAG_NO_START_SESSION flag by
         * 1. Preventing the use of a real session
         * 2. Setting the flag which is used in the cookie rewrite to prevent cookie values making it down
         *
         * All cookies set by the server must pass through Mage_Core_Model_Cookie or there'll be inconsistent behaviour
         */
        $this->isStatelessRequest = false;
        $frontControllerAction = $this->getFrontControllerAction();
        if ($frontControllerAction instanceof Mage_Core_Controller_Varien_Action) {
            if ($frontControllerAction->getFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_START_SESSION)) {
                $this->isStatelessRequest = true;
            }
        }

    }

    public function isBot()
    {
        return $this->isBot;
    }

    public function isStatelessRequest()
    {
        return $this->isStatelessRequest;
    }

    protected function getBlackHoleConfig()
    {
        if ($this->config === null) {
            $this->config = Mage::getConfig()->getNode('global/aoeblackholesession');
        }
        return $this->config;
    }

    /**
     * @return Mage_Core_Controller_Varien_Action|false
     * @author Luke Rodgers <lr@amp.co>
     */
    protected function getFrontControllerAction()
    {
        $frontController = Mage::app()->getFrontController();
        if ($frontController instanceof Mage_Core_Controller_Varien_Front) {
            $action = $frontController->getAction();
            if ($action instanceof Mage_Core_Controller_Varien_Action) {
                return $action;
            }
        }
        return false;
    }
}
