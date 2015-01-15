<?php
/**
* @copyright		Team ReadyBytes
* @license			GNU GPL 3
* @package			paycart
* @contact			support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) OR  die( 'Restricted access' );

jimport('joomla.filesystem.file');

$fileName = JPATH_SITE. '/components/com_paycart/paycart/helpers/jevent.php';

if (!JFile::exists($fileName)) {
	JFactory::getApplication()->enqueueMessage('Please disable paycart system plugin', 'warning');
	return true;
}

//include paycart joomla event handler
require_once $fileName;

$dispatcher = JDispatcher::getInstance();

// register User events
$dispatcher->register('onUserLogin', 'PaycartHelperJevent');

// register PaycartSystem  events
$dispatcher->register('onPaycartViewAfterRender', 'PaycartHelperJevent');

class plgSystemPaycart extends JPlugin
{}
