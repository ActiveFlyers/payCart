<?php

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for the back-end extension intallation screen.
 */
class InstallerPage extends AdminPage
{
	
	protected $waitForXpath =  "//a[@href='#upload']";		// Upload tab
	protected $url 			= 'administrator/index.php?option=com_installer';	
		
	public $tabs 			= array('#upload', '#directory', '#url');		// href
	public $tabLabels 		= array('Upload Package File', 'Install from Directory', 'Install from URL');
	
	/**
	 * Array of expected id values for toolbar div elements
	 * @var array
	 */
	public $toolbar = array (
			'Uninstall' => 'toolbar-delete'
	);
	
	
	public function install($installable, $install_by='install_from_url') {
		$this->$install_by($installable);
	}
	
	public function uninstall($kit_name) 
	{
		$this->clickMenu('Extension Manage');
		$this->setField('Filter', $kit_name);
		$this->clickButton("//button[@class='btn tip hasTooltip'][@type='submit'][@ data-original-title='Search']");
		// RBTODO:: Be ensured your extension type
		$this->driver->findElement(By::name("checkall-toggle"))->click();
		$this->clickToolbar('Uninstall');
		//RBTODO:: Ensure extension uninstall
	}
	
	
	protected function install_from_url($installable) 
	{
		$tabLink = $this->driver->findElement(By::xPath("//a[@href='#url']"));
		$tabLink->click();
		
		$this->setField('Install URL', $installable);
		$this->clickButton("//input[@class='btn btn-primary'][@type='button'][@onclick='Joomla.submitbutton4()']");
		$this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath));
		
		$this->test->assertFalse($this->checkForNotices());
		//echo "\n ALERT::".$this->getAlertMessage()."\n";
	}
	
	// RbTODO:: Complete it when you required	
	protected function upload_package_file($installable) {
		$this->setField('Package File', $installable);
		$this->clickButton("//input[@class='btn btn-primary'][@type='button'][@value='Upload & Install']");
		$this->driver->waitForElementUntilIsPresent(By::xPath("//a[@href='#upload']"));
	}
	
	// RbTODO:: Complete it when you required	
	protected function install_from_directory($installable) {
		$this->setField('Install Directory', $installable);
		$this->clickButton("//input[@class='btn btn-primary'][@type='button'][@value='Upload & Install']");
		$this->driver->waitForElementUntilIsPresent(By::xPath("//a[@href='#upload']"));
	}
		
	/**
	 * 
	 * Set file value as per lable name at Intallation page
	 * @param unknown_type $label
	 * @param unknown_type $value
	 */
	public function setField($label, $value)
	{
		switch ($label)
		{
			case 'Package File':
				$id = 'install_package';
				break;
			case 'Install Directory': 
				$id = 'install_directory';
				break;
			case 'Install URL':
				$id = 'install_url';
				break;
			case 'Filter' :
				$id="filter_search";
		}
		
		$this->driver->findElement(By::id($id))->clear();
		$this->driver->findElement(By::id($id))->sendKeys($value);
	}
	
	/**
	 * 
	 * Set class property
	 * @param $prop Property name
	 * @param $value Property value
	 * @return return previous value
	 */
	public function set($prop, $value) 
	{
		$previousValue = @$this->$prop;
		$this->$prop = $value;
		
		return $previousValue;
	}
}
