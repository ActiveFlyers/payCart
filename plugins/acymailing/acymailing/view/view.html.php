<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * acymailing Html View
 * 
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminHtmlViewAcymailing extends PaycartAdminBaseViewAcymailing
{
	function display($tpl=null)
	{
		$list = $this->getAcymailingList();
		$categories = PaycartAPI::getCategories();
		$categories	= array_values($categories);
		foreach ($categories as $id => $category){
			$categories[$id]->htmlToShow =  str_repeat(html_entity_decode('&mdash; '), ($category->level - 1)<0?0:($category->level - 1)); 
		}
		
		$categoryAcymailingMapData     = $this->getCategoryAcymailingMapData();
		foreach ($categoryAcymailingMapData as $key => $value){
			$data[$key] = json_decode($value['acymailing_groups']);
		}
		
		$this->assign('categoryAcymailingMapData',$data);
		$this->assign('acymailingList',$list);
		$this->assign('categories', $categories);
		
		$this->setTpl('grid');
		return true;
	}
	
	//get list of acymailing groups
	public function getAcymailingList()
	{
		$query = new Rb_Query();
		return $query->select(' `listid` as list_id, `name` ')
		             ->from('`#__acymailing_list`')
		             ->dbLoadQuery()
		             ->loadObjectList('list_id');
	}
	
	
	public function getCategoryAcymailingMapData()
	{
		$query = new Rb_Query();
		return $query->select(' `object_id` as category_id, `acymailing_groups` ')
		             ->from('`#__paycart_acymailing`')
		             ->dbLoadQuery()
		             ->loadAssocList('category_id');
	}
}