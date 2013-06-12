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
	
	
	public function test_install_PayCart() {
		$this->_install_paycart();
		//$this->assertTrue(false);
		return true;
	}
	
	/**
	 *  @depends test_install_PayCart
	 */
	public function test_uninstall_PayCart($return) {
		$this->installerPage->uninstall('paycart');
	}
	
	/**
	 * @depends test_install_PayCart
	 */	
	public function test_upgrade_PayCart() {
		$this->_install_paycart();
		$this->_install_paycart();
	}
	
	private function _install_paycart() {
		$kit_name = $this->cfg->host .'/'.$this->cfg->path.'/'.$this->cfg->extension;
		$this->installerPage->install($kit_name);
		$this->assertNotNull($this->driver->findElement(By::xPath("//a[@href='index.php?option=com_paycart']")));
	}
		
}