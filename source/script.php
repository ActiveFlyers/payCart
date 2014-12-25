<?php
/**
* @copyright		Team ReadyBytes
* @license			GNU GPL 3
* @package			paycart
* @subpackage		Backend
*/
if(defined('_JEXEC')===false) die();

class Com_paycartInstallerScript
{
	/**
	 * System invokes this event just before install/update/uninstall method
	 * Enter description here ...
	 * @param string $type is the type of change {install, update or discover_install}
	 * @param Object $parent, is the class which is calling this method
	 
	 * @return void
	 */
	public function preflight( $type, $parent ) 
	{
		$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
		if(!JFolder::exists(JPATH_SITE.'/images/cart')){
			JFolder::create(JPATH_SITE.'/images/cart');
			JFile::write(JPATH_SITE.'/images/cart/index.html', $data);			
		}
		if(!JFolder::exists(JPATH_SITE.'/images/cart/thumbs')){
			JFolder::create(JPATH_SITE.'/images/cart/thumbs');
			JFile::write(JPATH_SITE.'/images/cart/thumbs/index.html', $data);			
		}
		if(!JFolder::exists(JPATH_SITE.'/images/cart/optimized')){
			JFolder::create(JPATH_SITE.'/images/cart/optimized');
			JFile::write(JPATH_SITE.'/images/cart/optimized/index.html', $data);
		}
		
		if(!JFolder::exists(JPATH_SITE.'/images/cart/squared')){
			JFolder::create(JPATH_SITE.'/images/cart/squared');
			JFile::write(JPATH_SITE.'/images/cart/squared/index.html', $data);
		}
	}

	/**
	 * System invoke this function on installation, 
	 * here we can do additional work that is required with installation
	 */
	public function install($parent)
	{		
		$lang = JFactory::getLanguage()->getTag();
		$sql = "INSERT IGNORE INTO `#__paycart_config` values ('localization_default_language', '".$lang."'), ('localization_supported_language', '".json_encode(array($lang))."')";
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		$db->query();
		
		$this->installExtensions();

		$extensions 	= array();
		$extensions[] 	= array('type'=>'system', 'name'=>'paycart');

		$this->changeExtensionState($extensions);
		return true;
	}

	/**
	 * System invoke this function on uninstallation, 
	 * here we can do additional work that is required with uninstallation
	 */
	function uninstall($parent)
	{
		$db = JFactory::getDBO();
		$query = "SELECT * FROM `#__extensions` WHERE `type`='plugin' AND `element`='paycart' AND `folder`='system'";
		$db->setQuery($query);
		$result = $db->loadObjectList('element');
		
		if(isset($result['paycart']))
		{
			$state=0;
			$extensions[] 	= array('type'=>'system', 'name'=>'paycart');
			$this->changeExtensionState($extensions, $state);
		}
		
		return true;
	}
	
	function update($parent)
	{
		return true;
	}
	
	/**
	 * 
	 * System invoke this method after an install/update/uninstall method
	 * @param string $type is the type of change {install, update or discover_install}
	 * @param Object $parent, is the class which is calling this method
	 
	 * @return void
	 * 
	 * @since 1.0
	 * @author mManishTrivedi
	 */
	public function postFlight( $type, $parent ) 
	{
		// Create default Front end menus
		require_once JPATH_ADMINISTRATOR.'/components/com_paycart/install/script/menu.php';
		PaycartInstallScriptMenu::createMenus();
	}
	
	/**
	 * Install the extensions located at the given path
	 * 
	 * @param $actionPath : Path from which to install extensions
	 * @param $delFolder : decide whether to delete the given folder ($actionPath) after installation or not
	 */
	function installExtensions($actionPath=null,$delFolder=true)
	{
		//if no path defined, use default path
		if($actionPath==null)
			$actionPath = dirname(__FILE__).'/admin/install/extensions';

		//get instance of installer
		$installer =  new JInstaller();

		$extensions	= JFolder::folders($actionPath);

		//no extension to install
		if(empty($extensions))
			return true;

		//install all extensions
		foreach ($extensions as $extension)
		{
			$msg = " ". $extension . ' : Installed Successfully ';

			// Install the packages
			if($installer->install("{$actionPath}/{$extension}")==false){
				$msg = " ". $extension . ' : Installation Failed. Please try to reinstall. [Supportive plugin/module for paycart]';
			}

			//enque the message
			JFactory::getApplication()->enqueueMessage($msg);
		}

		if($delFolder){
			$delPath = JPATH_ADMINISTRATOR.'/components/com_paycart/install/extensions';
			JFolder::delete($delPath);
		}

		return true;
	}

	/**
	 * 
	 * Change state of given extensions
	 * 
	 * @param $extensions : array containing type and name of the extensions
	 * @param $state : state to change for the given extensions
	 */
	function changeExtensionState($extensions = array(), $state = 1)
	{
		if(empty($extensions)){
			return true;
		}

		$db		= JFactory::getDBO();
		$query		= 'UPDATE '. $db->quoteName( '#__extensions' )
				. ' SET   '. $db->quoteName('enabled').'='.$db->Quote($state);

		$subQuery = array();
		foreach($extensions as $extension => $value){
			$subQuery[] = '('.$db->quoteName('element').'='.$db->Quote($value['name'])
				    . ' AND ' . $db->quoteName('folder').'='.$db->Quote($value['type'])
			            .'  AND `type`="plugin"  )   ';
		}

		$query .= 'WHERE '.implode(' OR ', $subQuery);

		$db->setQuery($query);
		return $db->query();
	}
}