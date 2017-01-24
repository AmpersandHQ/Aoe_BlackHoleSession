<?php
class SessionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->nuke();
        parent::setUp();
    }

    private function getSessionFiles()
    {
        $files = [];
        foreach (glob(Mage::getBaseDir('session') . '/*') as $file) {
            if (is_file($file)) {
                $files[] = $file;
            }
        }
        return $files;
    }

    public function tearDown()
    {
        $this->nuke();
        parent::tearDown();
    }

    protected function nuke()
    {
        unset($_SESSION);
        unset($_SERVER['REQUEST_URI']);
        unset($_SERVER['HTTP_USER_AGENT']);

        foreach ($this->getSessionFiles() as $file) {
            unlink($file);
        }

        Mage::reset();
        Mage::app();
    }

    public function testFileSessionsStoreData()
    {
        /**
         * @see https://github.com/OpenMage/magento-mirror/blob/magento-1.9/app/code/core/Mage/Core/Controller/Varien/Action.php#L493
         */
        Mage::getModel('core/session', array('name' => 'frontend'))->start();
        session_write_close();

        $this->assertCount(1, $this->getSessionFiles(), 'A session file should exist');
    }

    public function testFakeSessionsStoreNoData()
    {
        Mage::getConfig()->setNode('global/aoeblackholesession/bot_regex', '/^elb-healthchecker/i');
        $_SERVER['HTTP_USER_AGENT'] = 'elb-healthchecker';

        Mage::getModel('core/session', array('name' => 'frontend'))->start();
        session_write_close();

        $this->assertCount(0, $this->getSessionFiles(), 'No session file should exist');
    }

    /**
     * @dataProvider botDataProvider
     */
    public function testUserAgents($botRegex, $userAgent, $expectedSessionSaveMethod, $expectedSessionCount)
    {
        Mage::getConfig()->setNode('global/aoeblackholesession/bot_regex', $botRegex);
        $_SERVER['HTTP_USER_AGENT'] = $userAgent;

        $session = Mage::getModel('core/session', array('name' => 'frontend'))->start();

        $this->assertEquals($expectedSessionSaveMethod, (string)$session->getSessionSaveMethod());
        session_write_close();

        $this->assertCount(
            $expectedSessionCount,
            $this->getSessionFiles(),
            "The wrong number of session files was encountered"
        );
    }

    public function botDataProvider()
    {
        return [
            ['/^elb-healthchecker/i', 'elb-healthchecker', 'user', 0],
            ['/^elb-healthchecker/i', 'chrome', 'files', 1],
        ];
    }

    /**
     * @dataProvider requestUriDataProvider
     */
    public function testRequestUri($uriRegex, $requestUri, $expectedSessionSaveMethod, $expectedSessionCount)
    {
        Mage::getConfig()->setNode('global/aoeblackholesession/uri_regex', $uriRegex);
        $_SERVER['REQUEST_URI'] = $requestUri;

        $session = Mage::getModel('core/session', array('name' => 'frontend'))->start();

        $this->assertEquals($expectedSessionSaveMethod, (string)$session->getSessionSaveMethod());
        session_write_close();

        $this->assertCount(
            $expectedSessionCount,
            $this->getSessionFiles(),
            "The wrong number of session files was encountered"
        );
    }

    public function requestUriDataProvider()
    {
        return [
            ['^(some\/path\/here|another\/different\/path)$^', 'some/path/here', 'user', 0],
            ['^(another\/different\/path)$^', 'some/path/here', 'files', 1],
            ['', 'some/path/here', 'files', 1],
            ['^(some\/path\/here|another\/different\/path)$^', 'another/different/path', 'user', 0],
            ['^(some\/path\/here|another\/different\/path)$^', 'another/alternative/path', 'files', 1],
            ['^(some\/path\/here|another\/different\/path)$^', 'some/path/here/?var=1&foo=2', 'user', 0],
            ['^(some\/path\/here|another\/different\/path)$^', 'some/path/here?var=1&foo=2', 'user', 0],
        ];
    }
}
