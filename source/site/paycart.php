<?php
/**
* @copyright		Team ReadyBytes
* @license			GNU GPL 3
* @package			paycart
* @subpackage		Backend
* @contact			support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

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

// A simple way, by which we can exit after controller request
if(defined('PAYCART_EXIT')){
	exit(PAYCART_EXIT);
}

// lets complete the task by taking post-action
$controller->redirect();
