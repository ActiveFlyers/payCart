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

	public function install($parent)
	{		
		$lang = JFactory::getLanguage()->getTag();
		$sql = "INSERT IGNORE INTO `#__paycart_config` values ('localization_default_language', '".$lang."'), ('localization_supported_language', '".json_encode(array($lang))."')";
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		$db->query();
		return true;
	}

	function uninstall($parent)
	{
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
		;
	}
}
