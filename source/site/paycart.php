<?php
/**
* @copyright		Team ReadyBytes
* @license			GNU GPL 3
* @package			paycart
* @subpackage		Backend
* @contact			@contact@
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

if(!defined('RB_FRAMEWORK_LOADED')){
	JLog::add('RB Frameowork not loaded',JLog::ERROR);
}

require_once  dirname(__FILE__).'/paycart/includes.php';
$option	= 'com_paycart';
$view	= 'productcategory';
$task	= null;
$format	= 'html';

$controllerClass = PaycartHelper::findController($option, $view, $task, $format);

$controller =  PaycartFactory::getInstance($controllerClass, 'controller', 'Paycartsite');

// execute task
$controller->execute($task);

// lets complete the task by taking post-action
$controller->redirect();
