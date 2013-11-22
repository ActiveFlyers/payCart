<?php


class ProductGridTest extends RbWebdriverTestCase
{

	public function setUp()
	{
		parent::setUp();
		$cpPage = $this->doAdminLogin();
		//$this->installerPage = $cpPage->clickMenu('Extension Manager', 'InstallerPage');
	}

	/**
	 * Logout and close test.
	 */
	public function tearDown()
	{
		$this->doAdminLogout();
		parent::tearDown();
	}
	
	public function testGridDisplay()
	{
		$this->driver->get($this->cfg->host . $this->cfg->path . 'administrator/index.php?option=com_paycart');
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
		
}