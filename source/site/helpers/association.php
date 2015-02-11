<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Paycart 
* @author 		support+paycart@readybytes.in
*/

defined('_JEXEC') or die;

abstract class PaycartHelperAssociation
{
	public static function getAssociations($id = 0, $view = null, $task = null)
	{	
		require_once JPATH_SITE.'/components/com_paycart/paycart/functions.php';
			
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$view = is_null($view) ? $jinput->get('view') : $view;
		$task = is_null($task) ? $jinput->get('task') : $task;
		$id = empty($id) ? $jinput->get($view.'_id') : $id;
		$suffix = '';
		if ($id){
			$suffix = '&'.$view.'_id='.$id;
		}
			
		$config = paycart_getConfig();
		$supported_lang = $config['localization_supported_language'];

		$return = array();
		foreach ($supported_lang as $tag){					
			$return[$tag] = 'index.php?option=com_paycart&view='.$view.'&task='.$task.$suffix.'&language='.$tag;
		}
		
		return $return;
	}	
}
