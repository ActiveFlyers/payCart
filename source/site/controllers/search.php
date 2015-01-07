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
		$searchWord   			= $this->input->get('query',null,'STRING');
		$start					= $this->input->get('pagination_start',0);
		$postFilters   			= $this->input->get('filters',array(), 'ARRAY');
		
		$appliedCoreFilters     = isset($postFilters['core'])?$postFilters['core']:array();
		$appliedAttrFilters     = isset($postFilters['attribute'])?$postFilters['attribute']:array();
		$appliedSorting         = isset($postFilters['sort'])?$postFilters['sort']:'0';
		$filterHelper  		    = PaycartFactory::getHelper('filter');
		$formatter				= PaycartFactory::getHelper('format');
		$filterHelper->pagination_start	= $start;
		
		/* 
		 * set whether filters are also applied or only searching is there
		 * It is used to decide whether to load filter's options after including 
		 * applied filter condition or only through search word
		 */
		$isFiltersApplied = false;
		if(!empty($postFilters)){
			$isFiltersApplied = true;
		}

		// get products and total count
		list($result,$count) = $filterHelper->findProducts($appliedCoreFilters, $appliedAttrFilters, $searchWord, $appliedSorting);		
		
		/*
		 * First always load all filter options with only search word/categoryId
		 * It will be used to find out which options should be disabled, while filtering products 
		 */
		if($searchWord){ 
			//case of searching
			$availableCoreOptions      = $filterHelper->getAllCoreOptionsBySearchWord($searchWord);			
			$availableAttributeOptions = $filterHelper->getAllAttributeOptionsBySearchWord($searchWord);
		}else{
			//case of browsing
			$availableCoreOptions      = $filterHelper->getAllCoreOptionsByCategory($appliedCoreFilters['category']); 
			$availableAttributeOptions = $filterHelper->getAllAttributeOptionsByCategory($appliedCoreFilters['category']);
		}
		
		// build filter html and detail about each option from custom attributes
		// detail will be used in showing applied filters
		list($attrFiltersHtml, $appliedAttrDetail) = $this->_buildAttributeFilters($availableAttributeOptions,$appliedAttrFilters,$isFiltersApplied);
		
		$allAvailableCategories = PaycartFactory::getModel('productcategory')->loadRecords();
		
		// we will always display all the categories that is matching searchword+selected categoryId
		// other filters will not be considered 
		$tree = $this->_buildCategoryTree($allAvailableCategories,$availableCoreOptions['categories']);
		
		
		$filters         = new stdClass();
		$filters->core   = new stdClass();
		$filters->attribute = new stdClass();
		
		//core filter data related to product table itself 
		$filters->core->selectedCategoryId 	  = (isset($appliedCoreFilters['category']) && !empty($appliedCoreFilters['category']))
												? $appliedCoreFilters['category']
												: '';
		$filters->core->categories 		      = $allAvailableCategories;
		$filters->core->categoryTree		  = $tree;		
		
		$filters->core->minPriceRange         = floatval($availableCoreOptions['price']['min']);
		$filters->core->maxPriceRange   	  = floatval($availableCoreOptions['price']['max']);
		$filters->core->appliedPriceRange	  = (isset($appliedCoreFilters['price']) && !empty($appliedCoreFilters['price']))
												? array($appliedCoreFilters['price'] => $formatter->priceRange($appliedCoreFilters['price']))
												: array();
		
		$filters->core->minWeightRange	      = floatval($availableCoreOptions['weight']['min']);
		$filters->core->maxWeightRange        = floatval($availableCoreOptions['weight']['max']);
		$filters->core->appliedWeightRange	  = (isset($appliedCoreFilters['weight']) && !empty($appliedCoreFilters['weight']))
												? array($appliedCoreFilters['weight'] => $formatter->weightRange($appliedCoreFilters['weight']))
												: array();	
													
		$filters->core->appliedInStock		  = (isset($appliedCoreFilters['in_stock']) && !empty($appliedCoreFilters['in_stock']))
												? array('in_stock' => $appliedCoreFilters['in_stock'])
												: array();	
													
		//custom filters related to attributes
		$filters->attribute->filterHtml     	  = $attrFiltersHtml;		
		$filters->attribute->appliedAttr  		  = !empty($appliedAttrDetail)?$appliedAttrFilters:array();
		$filters->attribute->appliedAttrDetail   = $appliedAttrDetail;
		
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
		$view->assign('start',$start+$filterHelper->pagination_limit);
		$view->assign('showFilters',(!empty($result) || !empty($postFilters))?true:false);
		
		$this->setTemplate('result');
		
		return true;		
	}
	
	/**
	 * Ajaxified task to load more products
	 */
	function loadMore()
	{
		$searchWord   			= $this->input->get('query',null,'STRING');
		$start					= $this->input->get('pagination_start',0);
		$filters	   		    = $this->input->get('filters','', 'ARRAY');
		$appliedCoreFilters     = isset($filters['core'])?$filters['core']:array();
		$appliedAttrFilters     = isset($filters['attribute'])?$filters['attribute']:array();
		$appliedSorting         = isset($filters['sort'])?$filters['sort']:'0';
		$filterHelper  		    = PaycartFactory::getHelper('filter');
		$filterHelper->pagination_start	= $start;
		
		// get products and total count
		list($searchResult,$count) = $filterHelper->findProducts($appliedCoreFilters, $appliedAttrFilters, $searchWord, $appliedSorting);
		
		if(!empty($searchResult)){
			$products = $this->_buildProductsData($searchResult);
			
			$data = new stdclass();
			$data->products = $products;
			$data->pagination_start = $start = $start+$filterHelper->pagination_limit;
			
			$ajax 	  = PaycartFactory::getAjaxResponse();
			
			//if no more products available then remove loadMore button
			if($start > $count){
				$ajax->addScriptCall('$(".pc-loadMore").remove');	
			}
			
			$response = Array();
			$response['pagination_start']   = $start;
			$response['html']  	 = JLayoutHelper::render('paycart_product_list', $data); 
			$callback 			 = 'paycart.product.loadMore.success';
			
			// set call back function
			$ajax->addScriptCall($callback, json_encode($response));
		}
		
		return false;
	}
	
	/**
	 * build html of available attributes 
	 * 
	 * @param array $availableAttrOptions
	 * @param array $appliedAttrOptions
	 * @param boolean $isFiltersApplied
	 */
	function _buildAttributeFilters($availableAttrOptions,$appliedAttrOptions, $isFiltersApplied)
	{
		$newAttrFilters    = array();
		$appliedAttrData   = array();
		$attrFiltersHtml   = array();
		$attrOptions       = array();
		
		//filter attribute option according to the applied filters
		foreach ($availableAttrOptions as $data){
			//if filtering is applied, then for each attribute we need to load filter options that will enabled
			if($isFiltersApplied && !isset($attrOptions[$data->productattribute_id])){
				$attrOptions[$data->productattribute_id] = PaycartFactory::getHelper('filter')->getApplicableAttributeOptions($data->productattribute_id);	

				foreach ($attrOptions[$data->productattribute_id] as $optionData){
					$newAttrFilters[$optionData->productattribute_id][$optionData->productattribute_value]['productCount'] = $optionData->productCount;
					$newAttrFilters[$optionData->productattribute_id][$optionData->productattribute_value]['disabled'] = false;
				}
			}
			
			//Do not update values if record has already been processed
			//In case of filtering, add only disabled options. Rest options have already been add by above code 
			if(!isset($newAttrFilters[$data->productattribute_id][$data->productattribute_value])){
				$newAttrFilters[$data->productattribute_id][$data->productattribute_value]['productCount'] = ($isFiltersApplied)?0:$data->productCount;
				$newAttrFilters[$data->productattribute_id][$data->productattribute_value]['disabled'] = ($isFiltersApplied)?true:false; 
			}
		}
		
		foreach($newAttrFilters as $id=>$options){
			//if only one option is there then no need to add it to filter
			if(count($options) <=1 ){
				continue;
			}
			$instance = PaycartProductAttribute::getInstance($id);
			//check whether the attribute is filterable or not
			if($instance->isFilterable()){
				$attrFiltersHtml[$id]['name'] = $instance->getTitle();
				$attrFiltersHtml[$id]['html'] = $instance->getFilterHtml(isset($appliedAttrOptions[$id])?$appliedAttrOptions[$id]:array(), $options);
				
				//get options of the current attribute, will be used while showing applied filters
				$appliedAttrData[$id] = $instance->getOptions();
			}
		}
		
		return array($attrFiltersHtml, $appliedAttrData);
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
	protected function _buildCategoryTree($categories, $applicableCategories)
	{
		if(empty($applicableCategories)){
			return array();
		}

		$tree = array();
		
		//collect individual tree of each node
		foreach ($applicableCategories as $categoryId => $data){
			$tree[$categoryId] = PaycartProductcategory::getInstance($categoryId)->getTree();
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
        return empty($parent)?array():$parent[Paycart::PRODUCTCATEGORY_ROOT_ID];
	}
}