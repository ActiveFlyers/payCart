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