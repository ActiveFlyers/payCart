<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	paycartHelper
 * @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Format Helper
 * @author Gaurav Jain
 */

class PaycartHelperFilter extends JObject
{  
	public $_tempFilterQuery = array();
	public $limit = 5; 
	public $start = 0;
	
	//PCFIXME:: create a new file jFilter and move this function there
	public static function attributecode($value)
	{		
		// this function is called from filter, so need to be static
		$value = JApplicationHelper::stringURLSafe($value);
		$value = strtoupper($value);
		return str_replace('-', '_', $value); 
	}

	function findProducts(Array $coreFilters = array(),Array $customFilters = array(), $searchWord = '', $sort = '')
	{
		//if all filters are blank then do nothing
		if(empty($coreFilters) && empty($customFilters) && empty($searchWord)){
			return array();
		}
		
		$tempFilterQuery = array();
		$query           = new Rb_Query();
		
		//if search word exist 
		if($searchWord){
			$tempFilterQuery['searchWord'] = "`#__paycart_product_index` AS pi ON pi.product_id = pp.product_id AND ".
									 PaycartFactory::getModel('productindex')->buildWhereCondition($searchWord);
			$query->innerJoin($tempFilterQuery['searchWord']);
		}
		
		//custom attributes
		$conditionCustom = array();		
		if(!empty($customFilters)){
			foreach($customFilters as $attrId=>$selectedValues){
				$tempFilterQuery['attribute'][$attrId] = 'SELECT product_id FROM `#__paycart_productattribute_value`
					                      		  WHERE `productattribute_id` = '.$attrId.' AND 
					                      		 `productattribute_value` IN ('.implode(',', $selectedValues).') ';
				$conditionCustom[] = $tempFilterQuery['attribute'][$attrId];
			}		
			if(!empty($conditionCustom)){
				
				$query->innerJoin( '( SELECT `product_id` FROM ( '. implode(' UNION ALL ', $conditionCustom) .' ) 
				                      tpl GROUP BY `product_id` HAVING count(*) = '.count($customFilters).' ) AS av on av.product_id = pp.product_id');	
			}
		}
		
		$query->select('pp.`product_id`')
			  ->from('`#__paycart_product` AS pp')
			  ->limit($this->limit, $this->start);			  
			  
		//core filters
		if(isset($coreFilters['category']) && !empty($coreFilters['category'])){
			$category = PaycartProductcategory::getInstance($coreFilters['category'][0]);
			$tempFilterQuery['category'] = '(SELECT productcategory_id FROM `#__paycart_productcategory` 
											WHERE lft >='.$category->getLft().' AND rgt <= '.$category->getRgt().') AS pc 
							    			ON pp.productcategory_id = pc.productcategory_id';
			$query->innerJoin($tempFilterQuery['category']);
		}
		
		if(isset($coreFilters['price']) && !empty($coreFilters['price'])){
			$price = explode(',', $coreFilters['price']);
			$tempFilterQuery['price'] = 'pp.`price` >= '.$price[0] . ' AND pp.`price` <= '.$price[1];
			$query->where($tempFilterQuery['price']);
		}
		
		if(isset($coreFilters['weight']) && !empty($coreFilters['weight'])){
			$weight = explode(',', $coreFilters['weight']);
			$tempFilterQuery['weight'] = 'pp.`weight` >= '.$weight[0] . ' AND pp.`weight` <= '.$weight[1];
			$query->where($tempFilterQuery['weight']);
		}
	    
		//before setting stock related condition, save query that will be utilized in creating core and custom filters
	    $this->_tempFilterQuery = $tempFilterQuery;
	    
	    if(isset($coreFilters['in_stock']) && !empty($coreFilters['in_stock'])){
			$query->where('pp.`quantity` <> 0');
		}
		
		if(!empty($sort)){
			$this->addSorting($query,$sort);	
		}
		
		$records = $query->dbLoadQuery()->loadColumn();
		
		//now modify select and clear limit to get total number of result
		$count   = $query->clear('select')->clear('limit')->select('count(pp.`product_id`)')->dbLoadQuery()->loadResult();
		
		return array($records,$count);
	}
	
	function addSorting($query,$sort)
	{
		switch ($sort)
		{
			case 'hit'   		: $query->order('pp.`hit` DESC');
						 		  break;

			case 'created_date' : $query->order('pp.`created_date` DESC');
								  break;								
			
			case 'price_low' 	: $query->order('pp.`price` ASC');
								  break;
			
			case 'price_high' 	: $query->order('pp.`price` DESC');
								  break;
		}
		
		return $query;
	}
	
	public function getAllCustomFiltersBySearchword($searchWord)
	{
		$query = new Rb_Query();
		$model = PaycartFactory::getModel('productindex');
		
		return $query->select("`productattribute_id`,`productattribute_value` ,COUNT(av.`product_id`) as 'productCount'")
					 ->from('`#__paycart_productattribute_value` as av')
					 ->innerJoin('`#__paycart_product_index` as pi ON pi.product_id = av.product_id AND '.$model->buildWhereCondition($searchWord))
					 ->group('`productattribute_id`,`productattribute_value`')
					 ->dbLoadQuery()
					 ->loadObjectList();
	}
	
	public function getAllCoreFiltersBySearchword($searchWord)
	{
		$where = PaycartFactory::getModel('productindex')->buildWhereCondition($searchWord);
		$coreFilters = array();
		
		//load categories
		$query = new Rb_Query();
		$coreFilters['categories'] = $query->select('pp.productcategory_id')
										   ->from('#__paycart_product as pp')
										   ->innerJoin('`#__paycart_product_index` as pi ON pi.product_id = pp.product_id AND '.$where)
										   ->innerJoin('`#__paycart_productcategory` as pc ON pc.productcategory_id = pp.productcategory_id')
										   ->order('pc.lft ASC')
										   ->dbLoadQuery()
										   ->loadObjectList('productcategory_id');	
										   
		
		//PCTODO: Discuss - Whether to create separate function to load range data or not
												   
		//load price and weight range
		$query   = new Rb_Query();
		$results = $query->select('MIN(`price`) as minPrice, MAX(`price`) as maxPrice, MIN(`weight`) as minWeight, MAX(`weight`) as maxWeight')
						 ->from('#__paycart_product as pp')
						 ->innerJoin('`#__paycart_product_index` as pi ON pi.product_id = pp.product_id AND '.$where)
						 ->dbLoadQuery()
						 ->loadRow();
									  
		//price range
		$coreFilters['price']['min'] = $results[0];
		$coreFilters['price']['max'] = $results[1];
		
		//weight range
		$coreFilters['weight']['min'] = $results[2];
		$coreFilters['weight']['max'] = $results[3];
									  
		return $coreFilters;
	}
	
	public function getAllCustomFiltersByCategory($categoryId)
	{
		
		$category = PaycartProductcategory::getInstance($categoryId);
		$query    = new Rb_Query();
		
		return $query->select("`productattribute_id`,`productattribute_value` ,COUNT(av.`product_id`) as 'productCount'")
					 ->from('`#__paycart_productattribute_value` as av')
					 ->innerJoin('`#__paycart_productcategory` AS pc ON pc.`lft` >= '.$category->getLft().' AND pc.`rgt` <= '.$category->getRgt())
					 ->innerJoin('`#__paycart_product` AS pp ON pp.productcategory_id = pc.productcategory_id and pp.product_id = av.product_id')
					 ->group('`productattribute_id`,`productattribute_value`')
					 ->dbLoadQuery()
					 ->loadObjectList();
	}
	
	public function getAllCoreFiltersByCategory($categoryid)
	{		
		$category   = PaycartProductcategory::getInstance($categoryid);		
		$innerQuery = '`#__paycart_productcategory` AS pc ON lft >='.$category->getLft().' AND rgt <= '.$category->getRgt().' AND p.productcategory_id = pc.productcategory_id ';
		
		$query      = new Rb_Query();
		$coreFilter['categories']  = $query->select("p.`productcategory_id`")
			  							   ->from('`#__paycart_product` as p')
			  							   ->innerJoin($innerQuery)
			  							   ->order('pc.lft ASC')
			  							   ->dbLoadQuery()
			  							   ->loadObjectList('productcategory_id');
			  							   
			  						
		$results				   = $query->clear('select')
										   ->clear('join')
										   ->select('MIN(`price`) as minPrice, MAX(`price`) as maxPrice, MIN(`weight`) as minWeight, MAX(`weight`) as maxWeight')
			  							   ->innerJoin($innerQuery)
										   ->dbLoadQuery()
			  							   ->loadRow();
			  							   
		//price range
		$coreFilters['price']['min'] = $results[0];
		$coreFilters['price']['max'] = $results[1];
		
		//weight range
		$coreFilters['weight']['min'] = $results[2];
		$coreFilters['weight']['max'] = $results[3];
									  
		return $coreFilters;
	}
	
	public function getApplicableCustomFilters($attributeId)
	{   
		if(empty($this->_tempFilterQuery)){
			return array();
		}
		
		$query = new Rb_Query();			
		$query->innerJoin('('.$this->_buildOptionsFilterQuery(array('attribute' => $attributeId)).') AS s ON s.product_id = av.product_id ');
		
		return $query->select("`productattribute_id`,`productattribute_value` ,COUNT(av.`product_id`) as 'productCount'")
					 ->from('`#__paycart_productattribute_value` as av')
					 ->where('`productattribute_id` = '. $attributeId)
					 ->group('`productattribute_id`,`productattribute_value`')
					 ->dbLoadQuery()
					 ->loadObjectList();
	}
	
	public function getApplicableCategories()
	{
		if(empty($this->_tempFilterQuery)){
			return array();
		}
		
		$query = new Rb_Query();
		return $query->select("p.`productcategory_id`")
  				     ->from('`#__paycart_product` as p')
  				     ->innerJoin('('.$this->_buildOptionsFilterQuery(array('price','weight','attribute')).') AS s ON s.product_id = p.product_id ')
  				     ->dbLoadQuery()
  				     ->loadObjectList('productcategory_id');
	}
	
	function _buildOptionsFilterQuery($ignore = array())
	{
		$innerQuery      = new Rb_Query();
		$tempFilterQuery = $this->_tempFilterQuery;
		
		if(!in_array('searchWord',$ignore) && isset($tempFilterQuery['searchWord'])){
			$innerQuery->innerJoin($tempFilterQuery['searchWord']);
		}

		if( isset($tempFilterQuery['attribute']) && isset($ignore['attribute']) && !empty($ignore['attribute']) ){
			// unset the conditions of the given attribute id, when fetching options for the same attribute
			// otherwise only the selected option of this attribute will be fetched
			// but we want other options also according to the other selected filterAttribute options 
			unset($tempFilterQuery['attribute'][$ignore['attribute']]);

			$tempCondition = array();
			foreach ($tempFilterQuery['attribute'] as $id => $value){
				$tempCondition[] = $value;
			}
			if(!empty($tempCondition)){	
				$innerQuery->innerJoin( '( SELECT `product_id` FROM ( '. implode(' UNION ALL ', $tempCondition) .' ) tpl GROUP BY `product_id` HAVING count(*) = '.count($tempFilterQuery['attribute']).' ) AS av on av.product_id = pp.product_id');
			}
		}
		
		$innerQuery->select('pp.`product_id`')
		 		   ->from('`#__paycart_product` AS pp');
		 
		if(isset($tempFilterQuery['category']) && !in_array('category',$ignore)){
			$innerQuery->innerJoin($tempFilterQuery['category']);
		}
		
		if(isset($tempFilterQuery['price']) && !in_array('price',$ignore) ){
			$innerQuery->where($tempFilterQuery['price']);
		}
		
		if(isset($tempFilterQuery['weight']) && !in_array('weight',$ignore) ){
			$innerQuery->where($tempFilterQuery['weight']);
		}
		
		return $innerQuery->__toString();
	}
}