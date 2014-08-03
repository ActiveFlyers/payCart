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
	}

	public function install($parent)
	{		
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
}