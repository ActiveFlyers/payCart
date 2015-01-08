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
		//check minimum requirement for paycart
		$this->checkMinimumRequirements();
		
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
		
		//check and install rbframework
		return $this->preflightRbFramework($type, $parent);
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
		$this->_addScript();
	}

	//Redirects After Installation
	function _addScript()
	{
		?>
			<script type="text/javascript">
				window.onload = function(){	
				  setTimeout("location.href = 'index.php?option=com_paycart';", 100);
				}
			</script>
		<?php
	}
	
	/**
     * Check minimum php and joomla version
     */
	public function checkMinimumRequirements()
	{
		$msg = '';
		
		//check minimum php version
		if (!version_compare(PHP_VERSION, '5.3.10', 'ge')){
			$msg .= "<p>You need PHP 5.3.10 or later to install this component</p>";
		}

		// Check the minimum Joomla! version
		if (!version_compare(JVERSION, '3.3', 'ge')){
			$msg .= "<p>You need Joomla! 3.3 or later to install this component</p>";
		}
		
		if(!empty($msg)){
			JFactory::getApplication()->redirect('index.php?option=com_installer&view=install', $msg, 'error');
		}
		
		return true;
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

	/**
     * Check and install rbframework
     */
	public function preflightRbFramework($type, $parent)
	{
		if ($type != 'install' && $type != 'update'){
			return true;
		}

		$message 	= JText::_('ERROR_RB_NOT_FOUND : RB-Framework not found. Please refer <a href="http://www.readybytes.net/support/forum/knowledge-base/201257-error-codes.html" target="_blank">Error Codes </a> to resolve this issue.');
		// get content for rbframework version
		$file_url 	= 'http://pub.readybytes.net/rbinstaller/update/live.json';
		$link 		= new JURI($file_url);
		$curl 		= new JHttpTransportCurl(new JRegistry());
		$response 	= $curl->request('GET', $link);
	
		if($response->code != 200){
			JFactory::getApplication()->redirect('index.php?option=com_installer&view=install', $message, 'error');
		}

		$content 	= json_decode($response->body, true);
		if(!isset($content['rbframework']) || !isset($content['rbframework']['file_path'])){
			JFactory::getApplication()->redirect('index.php?option=com_installer&view=install', $message, 'error');
		}

		// check if already exists
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('*')
			  ->from($db->quoteName('#__extensions'))
			  ->where('`type` 		= '.$db->quote('plugin'))
			  ->where('`folder` 	= '.$db->quote('system'))
			  ->where('`client_id` 	= 0')
			  ->where('`element` 	= '.$db->quote('rbsl'));

		$db->setQuery($query);
		$result = $db->loadObject();

		//when rbframework is not already installed
		if (!$result) {
			$this->_installRBFramework($content['rbframework']);
			$this->changeExtensionState(array(array('type'=>'system', 'name'=>'rbsl')));
			return true;
		}

		$query	= $db->getQuery(true);
		$query->select('*')
			  ->from($db->quoteName('#__extensions'))
		      ->where('`type` = '.$db->quote('component'). ' AND ( `element` LIKE '.$db->quote('com_jxiforms') .' OR `element` LIKE '.$db->quote('com_payinvoice').')');
		
		$db->setQuery($query);
		$installed_extensions = $db->loadObjectList();

		//when no dependent extension is installed, install framework
		if (!$installed_extensions){
			$this->_installRBFramework($content['rbframework']);
			$this->changeExtensionState(array(array('type'=>'system', 'name'=>'rbsl')));
			return true;
		}
		else {
			$params 				= json_decode($result->manifest_cache, true);
			$latest_rb_version 		= explode('.', $content['rbframework']['version']);
			$installed_rb_version 	= explode('.', $params['version']);
	
			//if there is no change in the major version of rbframework then install else show message
			if(version_compare($installed_rb_version[0].'.'.$installed_rb_version[1], $latest_rb_version[0].'.'.$latest_rb_version[1]) == 0){
				$this->_installRBFramework($content['rbframework']);
				if(!$result->enabled){
					$this->changeExtensionState(array(array('type'=>'system', 'name'=>'rbsl')));
				}
				return true;
			}

			$message = JText::_('ERROR_RB_MAJOR_VERSION_CHANGE : Major version change in the RB-Framework. Refer <a href="http://www.readybytes.net/support/forum/knowledge-base/201257-error-codes.html" target="_blank">Error Codes </a> to resolve this issue.');
			JFactory::getApplication()->redirect('index.php?option=com_installer&view=install', $message, 'error');
		}
		return true;
	}

	protected function _installRBFramework($content)
	{
		// get file
		$link 			= new JUri($content['file_path']);
		$curl			= new JHttpTransportCurl(new JRegistry());
		$response 		= $curl->request('GET', $link);
		$content_type 	= $response->headers['Content-Type'];

		if ($content_type != 'application/zip'){
			return false;
		}
		else {
			$response 	= $response->body;
		}

		//install rb-framework kit
		$random				= rand(1000, 999999);
		$tmp_file_name 		= JPATH_ROOT.'/tmp/'.$random.'item_rbframework'.'_'.$content['version'].'.zip';
		$tmp_folder_name 	= JPATH_ROOT.'/tmp/'.$random.'item_rbframework'.'_'.$content['version'];
	
		// create a file
		JFile::write($tmp_file_name, $response);
		jimport('joomla.filesystem.archive');
		jimport( 'joomla.installer.installer' );
		jimport('joomla.installer.helper');
		JArchive::extract($tmp_file_name, $tmp_folder_name);

		$installer = new JInstaller;
		if($installer->install($tmp_folder_name)){
			$response = true;
		}
		else{
			$response = false;
		}

		if (JFolder::exists($tmp_folder_name)){
			JFolder::delete($tmp_folder_name);
		}

		if (JFile::exists($tmp_file_name)){
			JFile::delete($tmp_file_name);
		}

		return $response;
	}
	
}