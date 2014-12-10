<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @author		rimjhim
* @contact		support+paycart@readybytes.in
* 
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/**
 * 
 * Search Controller
 * @author rimjhim
 *
 */

class PaycartSiteControllerSearch extends PaycartController 
{
	public function getModel($name = '', $prefix = '', $config = array())
	{
		return null;
	}

	public function filter()
	{
		$searchWord   			= $this->input->get('q',null,'STRING');
		$start					= $this->input->get('start',0);
		$filters	   			= $this->input->get('filters','', 'ARRAY');
		$limit					= 5; //PCFIXME: load from configuration
		
		$appliedCoreFilters     = isset($filters['core'])?$filters['core']:array();
		$appliedCustomFilters   = isset($filters['custom'])?$filters['custom']:array();
		$appliedSorting         = isset($filters['sort'])?$filters['sort']:'0';
		$filterHelper  		    = PaycartFactory::getHelper('filter');
		$formatter				= PaycartFactory::getHelper('format');
		$filterHelper->start	= $start;
		
		/* 
		 * set whether filters are also applied or only searching is there
		 * It is used to decide whether to load filter's options after including 
		 * applied filter condition or only through search word
		 */
		$isFiltersApplied = false;
		if(!empty($filters)){
			$isFiltersApplied = true;
		}
				
		/*
		 * First always load all filter options with only search word/categoryId
		 * It will be used to find out which options should be disabled, while filtering products 
		 */
		if($searchWord){ 
			//case of searching
			$availableCoreFilters   = $filterHelper->getAllCoreFiltersBySearchWord($searchWord);			
			$availableCustomFilters = $filterHelper->getAllCustomFiltersBySearchWord($searchWord);
		}else{
			//case of browsing
			$availableCoreFilters   = $filterHelper->getAllCoreFiltersByCategory($appliedCoreFilters['category']); 
			$availableCustomFilters = $filterHelper->getAllCustomFiltersByCategory($appliedCoreFilters['category']);
		}
		
		// get products and total count
		list($result,$count) = $filterHelper->findProducts($appliedCoreFilters, $appliedCustomFilters, $searchWord, $appliedSorting);
		
		// build filter html and detail about each option from custom attributes
		// detail will be used in showing applied filters
		list($customFiltersHtml, $appliedAttrDetail) = $this->_buildCustomFilters($availableCustomFilters,$appliedCustomFilters,$isFiltersApplied);
		
		$allAvailableCategories = PaycartFactory::getModel('productcategory')->loadRecords();
		$tree = $this->_buildCategoryTree($allAvailableCategories);
		
		
		$filters         = new stdClass();
		$filters->core   = new stdClass();
		$filters->custom = new stdClass();
		
		//core filter data related to product table itself 
		$filters->core->selectedCategoryId 	  = (isset($appliedCoreFilters['category']) && !empty($appliedCoreFilters['category']))
												? $appliedCoreFilters['category']
												: '';
		$filters->core->categories 		      = $allAvailableCategories;
		$filters->core->categoryTree		  = $tree;		
		
		$filters->core->minPriceRange         = floatval($availableCoreFilters['price']['min']);
		$filters->core->maxPriceRange   	  = floatval($availableCoreFilters['price']['max']);
		$filters->core->appliedPriceRange	  = (isset($appliedCoreFilters['price']) && !empty($appliedCoreFilters['price']))
												? array($appliedCoreFilters['price'] => $formatter->priceRange($appliedCoreFilters['price']))
												: array();
												
		$filters->core->minWeightRange	      = floatval($availableCoreFilters['weight']['min']);
		$filters->core->maxWeightRange        = floatval($availableCoreFilters['weight']['max']);
		$filters->core->appliedWeightRange	  = (isset($appliedCoreFilters['weight']) && !empty($appliedCoreFilters['weight']))
												? array($appliedCoreFilters['weight'] => $formatter->weightRange($appliedCoreFilters['weight']))
												: array();	
													
		$filters->core->appliedInStock		  = (isset($appliedCoreFilters['in_stock']) && !empty($appliedCoreFilters['in_stock']))
												? array('in_stock' => $appliedCoreFilters['in_stock'])
												: array();	
													
		//custom filters related to attributes
		$filters->custom->filterHtml     	  = $customFiltersHtml;		
		$filters->custom->appliedAttr  		  = !empty($appliedAttrDetail)?$appliedCustomFilters:array();
		$filters->custom->appliedAttrDetail   = $appliedAttrDetail;
		
		//other common data
		$filters->searchWord				  = $searchWord;
		$filters->currency					  = $formatter->currency(PaycartFactory::getConfig()->get('localization_currency'));
		$filters->weightUnit				  = PaycartFactory::getConfig()->get('catalogue_weight_unit');
		
		$view = $this->getView();
		$view->assign('products',$this->_buildProductsData($result));
		$view->assign('count',$count);
		$view->assign('appliedSort',$appliedSorting);
		$view->assign('sortingOptions',paycart::getSortingOptions());
		$view->assign('filters',$filters);
		$view->assign('start',$start+$limit);
		
		$this->setTemplate('result');
		
		return true;		
	}
	
	/**
	 * Ajaxified task to load more products
	 */
	function loadMore()
	{
		$searchWord   			= $this->input->get('q',null,'STRING');
		$start					= $this->input->get('start',0);
		$filters	   		    = $this->input->get('filters','', 'ARRAY');
		$appliedCoreFilters     = isset($filters['core'])?$filters['core']:array();
		$appliedCustomFilters   = isset($filters['custom'])?$filters['custom']:array();
		$appliedSorting         = isset($filters['sort'])?$filters['sort']:'0';
		$filterHelper  		    = PaycartFactory::getHelper('filter');
		$limit					= 5; //PCFIXME: load from configuration
		$filterHelper->start	= $start;
		
		// get products and total count
		list($searchResult,$count) = $filterHelper->findProducts($appliedCoreFilters, $appliedCustomFilters, $searchWord, $appliedSorting);
		
		if(!empty($searchResult)){
			$products = $this->_buildProductsData($searchResult);
			
			$data = new stdclass();
			$data->products = $products;
			$data->start = $start = $start+$limit;
			
			$ajax 	  = PaycartFactory::getAjaxResponse();
			
			//if no more products available then remove loadMore button
			if($start > $count){
				$ajax->addScriptCall('$(".pc-loadMore").remove');	
			}
			
			$response = Array();
			$response['start']   = $start;
			$response['html']  	 = JLayoutHelper::render('paycart_product_list', $data); 
			$callback 			 = 'paycart.product.loadMore.success';
			
			// set call back function
			$ajax->addScriptCall($callback, json_encode($response));
		}
		
		return false;
	}
	
	/**
	 * build html of available custom attributes 
	 * 
	 * @param array $availableCustomOptions
	 * @param array $appliedCustomOptions
	 * @param boolean $isFiltersApplied
	 */
	function _buildCustomFilters($availableCustomOptions,$appliedCustomOptions, $isFiltersApplied)
	{
		$newCustomFilters  = array();
		$appliedCustomData = array();
		$customFiltersHtml = array();
		$attrOptions       = array();
		
		//filter custom attribute option according to the applied filters
		foreach ($availableCustomOptions as $data){
			if($isFiltersApplied && !isset($attrOptions[$data->productattribute_id])){
				$attrOptions[$data->productattribute_id] = PaycartFactory::getHelper('filter')->getApplicableCustomFilters($data->productattribute_id);	

				foreach ($attrOptions[$data->productattribute_id] as $optionData){
					$newCustomFilters[$optionData->productattribute_id][$optionData->productattribute_value]['productCount'] = $optionData->productCount;
					$newCustomFilters[$optionData->productattribute_id][$optionData->productattribute_value]['disabled'] = false;
				}
			}
			
			if(!isset($newCustomFilters[$data->productattribute_id][$data->productattribute_value])){
				$newCustomFilters[$data->productattribute_id][$data->productattribute_value]['productCount'] = ($isFiltersApplied)?0:$data->productCount;
				$newCustomFilters[$data->productattribute_id][$data->productattribute_value]['disabled'] = ($isFiltersApplied)?true:false; 
			}
		}
		
		foreach($newCustomFilters as $id=>$options){
//			//if only one option is there then no need to add it to filter
//			if(count($options) <=1 ){
//				continue;
//			}
			$instance = PaycartProductAttribute::getInstance($id);
			$customFiltersHtml[$id]['name'] = $instance->getTitle();
			$customFiltersHtml[$id]['html'] = $instance->getFilterHtml(isset($appliedCustomOptions[$id])?$appliedCustomOptions[$id]:array(), $options);
			
			//get options of the current attribute, will be used while showing applied filters
			$appliedCustomData[$id] = $instance->getOptions();
		}
		
		return array($customFiltersHtml, $appliedCustomData);
	}
	
	/**
	 * build objects of stdclass from productIds  
	 * @param $searchResult - array of productIds 
	 */
	function _buildProductsData($result)
	{
		$products  = new stdClass();
		$formatter = PaycartFactory::getHelper('format');
		foreach ($result as $productId){
			$instance = PaycartProduct::getInstance($productId);
			$product  = (object)$instance->toArray();
			$product->price 			   = $formatter->amount($instance->getPrice(), true);
			$products->$productId 		   = $product;
			$products->$productId->inStock = PaycartFactory::getHelper('product')->isProductInStock($productId);
			$products->$productId->media   = $instance->getCoverMedia();
		}
		return $products;
	}
	
	/**
	 * return a category tree of applicable categories
	 * @param $categories : array of all the avaiable categories in system
	 */
	protected function _buildCategoryTree($categories)
	{
		$applicableCategories = PaycartFactory::getHelper('filter')->getApplicableCategories();
			
		$tree = array();
		
		//create individual tree of each node
		foreach ($applicableCategories as $categoryId => $data){
			$temp = $categoryId;
			$tree[$categoryId][] = $temp;
			
			while($temp != Paycart::PRODUCTCATEGORY_ROOT_ID){
				$tree[$categoryId][] = $categories[$temp]->parent_id;
				$temp = $categories[$temp]->parent_id;
			}
			
			$tree[$categoryId] = array_reverse($tree[$categoryId]);
		}
		
		//arranage all the parent and its children
		$parent =  array();
        foreach ($tree as $key=>$value){
        	foreach ($value as $catId) {
        		$temp = next($value);
        		if($temp){
        			$parent[$catId][$temp] = $temp;
        		}
        	}
        }

       	krsort($parent); // To move the root at last
        $parent = &$parent; //Imp: required to reflect changes within for loop
        $count  = 1;
       
        //make a common tree from the parents i.e. on root category
        foreach ($parent as $key => $value){
            $temp = array_slice($parent,$count, null, true);
            $done = false;
           
            foreach ($temp as $parentId => $data){
                if($done == true){
                    break;
                }
                foreach ($data as $id){
                    if($id == $key){
                        $parent[$parentId][$id] = $value;
                        $done = true;
                        break;
                    }
                }
            }
            $count++;
        }
        
        //we got tree structrue on root category
        return $parent[Paycart::PRODUCTCATEGORY_ROOT_ID];
	}
}