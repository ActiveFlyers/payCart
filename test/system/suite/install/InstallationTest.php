<?php


use SeleniumClient\By;

class InstallationTest extends RbWebdriverTestCase
{

	public function setUp()
	{
		parent::setUp();
		$cpPage = $this->doAdminLogin();
		$this->installerPage = $cpPage->clickMenu('Extension Manager', 'InstallerPage');
	}

	/**
	 * Logout and close test.
	 */
	public function tearDown()
	{
		$this->doAdminLogout();
		parent::tearDown();
	}
	
	
	public function testInstallPayCart() 
	{
		$this->_install_paycart();
		return true;
	}
	
	/**
	 *  @depends testInstallPayCart
	 */
	public function testUninstallPayCart($return) 
	{
		$this->installerPage->uninstall('paycart');
	}
	
	/**
	 * @depends testInstallPayCart
	 */	
	public function testUpgradePayCart() 
	{
		$this->_install_paycart();
		
		//After Paycart installation, we need to come back on installer page 
		$this->installerPage->clickMenu('Extension Manager', 'InstallerPage');
		
		$this->_install_paycart();
	}
	
	private function _install_paycart() 
	{
		$kit_name = $this->cfg->host .'/'.$this->cfg->path.'/'.$this->cfg->extension;
		$this->installerPage->set('waitForXpath', '//a[contains(@href, "index.php?option=com_paycart&view=product&task=new")]');
		$this->installerPage->install($kit_name);
		$this->assertNotNull($this->driver->findElement(By::xPath("//a[@href='index.php?option=com_paycart']")));
	}
		
}