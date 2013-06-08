<?php


class InstallTest extends RbWebdriverTestCase
{

	public function test_install_PayCart() {
		return ;
		
//		$this->deleteConfigurationFile();
//		$url = $this->cfg->host . $this->cfg->path . 'installation/';
//		$installPage = $this->getPageObject('InstallationPage', true, $url);
//		$installPage->install($this->cfg);
//		$cpPage = $this->doAdminLogin();
//		$gcPage = $cpPage->clickMenu('Global Configuration', 'GlobalConfigurationPage');
//		$gcPage->setFieldValue('Cache', 'OFF');
//		$gcPage->setFieldValue('Error Reporting', 'Development');
//		$gcPage->saveAndClose('ControlPanelPage');
//		$this->doAdminLogout();
	}

	public function test_uninstall_PayCart() {
		return ;
	}
	
	/**
	 * @depends
	 */	
	public function test_upgrade_PayCart() {
		return ;
	}
		
}