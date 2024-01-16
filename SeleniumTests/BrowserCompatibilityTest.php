<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/SeleniumTests/SeleneseTest.php to edit this template
 */

/**
 * Description of newSeleneseTest
 *
 * @author joseph-nicolasyazbek
 */
class BrowserCompatibilityTest extends PHPUnit_Framework_TestCase {

    /**
     * @var \RemoteWebDriver
     */
    protected $webDriver;

    protected function setUpWebDriver($browser) {
        $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => $browser);
        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
    }

    public function tearDown() {
        $this->webDriver->close();
    }

    protected $url = 'http://localhost/mediatekformation/public/index.php';

    /**
     * Test sur Google Chrome
     */
    public function testOnChrome() {
        $this->setUpWebDriver('chrome');
        $this->webDriver->get($this->url);
        $this->assertContains('Mediatek86', $this->webDriver->getTitle());
    }

    /**
     * Test sur Microsoft Edge
     */
    public function testOnEdge() {
        $this->setUpWebDriver('Firefox');
        $this->webDriver->get($this->url);
        $this->assertContains('Mediatek86', $this->webDriver->getTitle());
    }
}


