<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		team@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

if(!defined('RB_FRAMEWORK_LOADED')){
	JLog::add('RB Frameowork not loaded',JLog::ERROR);
}

require_once JPATH_SITE.'/components/com_paycart/paycart/includes.php';

// find the controller to handle the request
$option	= 'com_paycart';
$view	= 'dashboard';
$task	= null;
$format	= 'html';

$controllerClass = PaycartHelper::findController($option,$view, $task,$format);


$controller = PaycartFactory::getInstance($controllerClass, 'controller', 'Paycartadmin');

// execute task
try{
	$controller->execute($task);
}catch(Exception $e){
	PaycartHelper::handleException($e);
}

// lets complete the task by taking post-action
$controller->redirect();
