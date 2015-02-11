<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Paycart 
* @author 		support+paycart@readybytes.in
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

// define root category id
define('PAYCART_PRODUCTCATEGORY_ROOT_ID', 1);

/**
 * 
 * Joomla Invoke to transform an array of URL parameters ($query) into an array of segments.
 * {Encoding Paycart Urls}
 * @param Array $query array of URL parameters
 * 
 * @author mManishTrivedi
 * @since  1.0
 * 
 * @return Array of segments
 */
function paycartBuildRoute(&$query)
{
	$segments = array();
	
	/* @var $paycart_router PaycartRouter */
	$paycart_router = PaycartRouter::getInstance();
	
	$segments = $paycart_router->build($query);
	
	return $segments;
}

/**
 * 
 * Joomla invoke to transform an array of segments back into an array of URL parameters.
 * {Decoding Paycart Urls}
 * @param $segments 
 * 
 * @author mManishTrivedi
 * @since  1.0
 * 
 * @return Array of url parameter
 */
function paycartParseRoute($segments) 
{
	$query = Array();
	
	/* @var $paycart_router PaycartRouter */
	$paycart_router = PaycartRouter::getInstance();
	
	$query = $paycart_router->parse($segments);
	
	return $query;
}


class PaycartRouter extends Rb_Router
{
	protected 	$_component	= 'com_paycart';
	
	public function __construct()
	{
		require_once JPATH_SITE.'/components/com_paycart/paycart/functions.php';				
	}
	
	protected static $routes = 
		        		Array (
		        			//Productcategory {view}/{task}
		        			'productcategory/display'	=>	Array('productcategory_id'),
		        			
		        			//Product {view}/{task}
		        			'product/display'	=>	Array('product_id'),
		        		
							//Cart {view}/{task}
		        			'cart/display'	=>	Array(),
		        			'cart/checkout'	=>	Array(),
		        			'cart/buy'		=>	Array('product_id'),
		        			'cart/complete'	=>	Array('cart_id'),        		
		        		
		        			'account/display'=>	Array(),
		        			'account/order'	 =>	Array('order_id'),
		        			'account/address'=>	Array(),
		        			'account/login'	 =>	Array(),
		        			'account/setting'=>	Array(),
		        		);
	


	/**
	 * 
	 * Enter description here ...
	 * @param $name
	 * @param $prefix
	 */
	public static function getInstance($prefix = 'paycart')
    {
    	return parent::getInstance($prefix);
    }
    
	 
   /**
    * (non-PHPdoc)
    * @see plugins/system/rbsl/rb/rb/Rb_Router::_routes()
    */   
	protected function _routes($key)
    {
        if ( false == isset(static::$routes[$key]) ){
            return array();
        }

        return static::$routes[$key];
    }
    
    /**
     * 
     * Invoke to check key exist in route or not
     * @param String $key
     */
    protected function hasRouteKey($key)
    {
    	return isset(static::$routes[$key]);
    }
    
    /**
     * (non-PHPdoc)
     * @see plugins/system/rbsl/rb/rb/Rb_Router::_deSlugify()
     */
	protected function _deSlugify($var, &$segments, $parts)
    {
    	switch ($var) {
    		case 'productcategory_id' :
    			$route_path = implode('/', $segments);
    			// return productcategory id
    			return $this->productcategory_RouteToID($route_path);
    			
    		case 'product_id' :
    			$route_path = implode('/', $segments);
    			// return product_id
    			return $this->product_RouteToID($route_path);
    			
    			
    		default:
    			return parent::_deSlugify($var, $segments, $parts );
    		
    	}

    }

    
    /**
     * (non-PHPdoc)
     * @see plugins/system/rbsl/rb/rb/Rb_Router::_slugify()
     */
	protected function _slugify($query, $var)
    {
    	switch ($var) {
    		case 'productcategory_id' :
    			return $this->productcategory_IDtoRoute($query[$var], $query);
    		
    		case 'product_id' :
    			return $this->product_IDtoRoute($query[$var], $query);
    			
    		
    		default:
    			return $query[$var];
    		
    	}
    }
    
    
	
	/** 
	 * Invoke to translate route path (alias hierarchy) into id.
	 * @param string $route_path
	 * 
	 * @return numeric value The Category id if found, or return dafault categoru path if not found
	 */
	public function productcategory_RouteToID($route_path) 
	{	
		$segments = explode('/', $route_path);
		
		// get category level
    	$category_level = count($segments);
    	
    	//gat current url category alias from alias hierachy
    	$category_alias = $segments[$category_level-1];
		
		$lang_code = $this->_getPaycartCurrentLanguage();
		
		$query = "
					SELECT `productcategory_id` 
		 			FROM   `#__paycart_productcategory_lang`
		 			WHERE  `alias` = '$category_alias' AND
		 					`lang_code` = '$lang_code' AND
		 					`productcategory_id` IN 
		 						( 	
		 							SELECT `productcategory_id`
						 			FROM #__paycart_productcategory
						 			WHERE `level` = $category_level
		 						) 
				 ";
		
		$db = Rb_Factory::getDbo();

		// get all matched alias categories
		$productcategory_id_array = $db->setQuery($query)->loadColumn();
		
		// if product category deleted or alias changed then return root category id
		if (empty($productcategory_id_array)) {
			return JError::raiseError(404, "Category is not found.");
		}
		
		// if get only one value it means alias duplicacy not exist and return category id
		if (count($productcategory_id_array) == 1) {
			return array_shift($productcategory_id_array);
		}

		// need to iterate all hierachy and get actual category-id 
		// becoz $productcategory_id_array have duplicacy alias with same level
		foreach ($productcategory_id_array as $productcategory_id) {
			
			if ($route_path == $this->productcategory_IDtoRoute($productcategory_id)) {
				return $productcategory_id;
			}
		}

		return JError::raiseError(404, "Category is not found.");
	}
	
	
	/**
	 * 
	 * Invoke to translate product category id to route path (Alias hierachy)
	 * @param INT $productcategory_id
	 * 
	 * @return string value
	 */
	public function productcategory_IDtoRoute($productcategory_id, $query)
	{		
		if(isset($query['language'])){
			$lang_code = $query['language'];
		}
		else{
    		$lang_code = $this->_getPaycartCurrentLanguage();
		}
		
		$categories = $this->_getProductCategories($lang_code);
		
		$root_category_id = PAYCART_PRODUCTCATEGORY_ROOT_ID;
    	
    	$alias = array();
	    $category_id = $productcategory_id;
	    while($root_category_id != $category_id){
	    	$alias[] = $categories[$category_id]->alias;
	    	$category_id = $categories[$category_id]->parent_id;
	    }
	    	
	    $alias = array_reverse($alias);
	    return implode('/', $alias);
    	
	}
	
 	/** 
	 * Invoke to translate route path (alias hierarchy) into id.
	 * @param string $route_path
	 * 
	 * @return numeric value The Product id if found
	 */
	public function product_RouteToID($route_path) 
	{	
		$segments = explode('/', $route_path);
		
		//gat current url Product alias from segments
    	$product_alias = $segments[0];
		
    	
		$lang_code = $this->_getPaycartCurrentLanguage();
		
		$query = "
					SELECT `product_id` 
		 			FROM   `#__paycart_product_lang`
		 			WHERE 	`alias` = '$product_alias' AND
		 				 	`lang_code` = '$lang_code'  
				 ";
		
		$db = Rb_Factory::getDbo();

		// get matched alias product
		$product_id = $db->setQuery($query)->loadResult();
		
		// @PCTODO :: if product_id is not exist then dump to log table so admin can resolve this issue
		if (empty($product_id)) {
			return JError::raiseError(404, "Product is not found.");
		}
		
		return $product_id;	
	}
	
	
	/**
	 * 
	 * Invoke to translate product id to route path (Alias )
	 * @param INT $product_id
	 * 
	 * @return string value
	 */
	public function product_IDtoRoute($product_id, $query)
	{
		if(isset($query['language'])){
			$lang_code = $query['language'];
		}
		else{
    		$lang_code = $this->_getPaycartCurrentLanguage();
		}	
		
	
		$query = "
					SELECT `alias` 
		 			FROM   `#__paycart_product_lang`
		 			WHERE 	`product_id` = $product_id AND
		 				 	`lang_code` = '$lang_code'  
				 ";
		
		$db = Rb_Factory::getDbo();

		return $db->setQuery($query)->loadResult();
	}
	
	protected function _getPaycartCurrentLanguage()
	{	
		return paycart_getCurrentLanguage();
	}
	
	protected function _getProductCategories($lang)
	{
		return paycart_getProductCategories($lang);		
	}
	
 	public function build( &$query )
    {
    	$segments = parent::build($query);
   		if(isset($query['language'])){
			unset($query['language']);
		}
        return $segments;
    }
    
    
 	public function getSelectedMenu(&$query, $menus)
    {        
        //If item id is not set then we need to extract those
        $selMenu = null;
        
        //IMP : Itemid can be sent of current page itself, rather then , which should not be used        
        if($menus){
            $count      = 0;

			$lang_tag = JFactory::getLanguage()->getTag();
			if(isset($query['language'])){
				$lang_tag  = $query['language'];				
			}
			
            foreach($menus as $menu){
            	$matching = $this->_findMatchCount($menu->query,$query);
            	
             	// if language is set on menu
                if(isset($menu->language)){
                    $menu->language = trim($menu->language);

	                if ($matching > 0 && $menu->language == $lang_tag) {
            	    	//count matching
            	   		$matching++;
            	    }
            	}
                
                //current menu matches more
                if($matching > $count){
                    $count		= $matching;
                    $selMenu 	= $menu;
                }
            }
        }
        
        //assig ItemID of selected menu if any
        if($selMenu !== null){
            $query['Itemid'] = $selMenu->id;
        }
        
        //finally selected menu is
        if($selMenu === null){
            $selMenu = new stdClass();
            $selMenu->query = array();
            unset($query['Itemid']);
        }

        return $selMenu;
    }
    
	 // 	Load component menu records
    public function _getMenus()
    {
        if($this->_menus ===null){
			$this->_menus 	= Rb_Factory::getApplication()->getMenu('site')->getItems(array('component_id', 'language'),array(JComponentHelper::getComponent($this->_component)->id, null));
		}

		return $this->_menus;
    }
}
