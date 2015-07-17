<?php

/**
* @copyright        Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license           GNU/GPL, see LICENSE.php
* @package           Joomla.Plugin
* @subpackage        Paycart
* @contact                supportpaycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @author Garima Agal
*/
class PlgPaycartZoomimages extends RB_Plugin
{
	function onPaycartViewBeforeRender($view,$task)
	{
		if($view instanceof PaycartSiteBaseViewProduct && $task == 'display'){
			$productId = JFactory::getApplication()->input->get('product_id',0,'INT');
	
			//if product doesn't exist or having mobile devices then do nothing
			$product   = PaycartProduct::getInstance($productId);
			if(!$productId || PaycartFactory::getApplication()->client->mobile){
				return '';
			}
			
			$images = $product->getImages();
			
			if(empty($images)){
				return '';
			}
			
			$zoomType = $this->params->get('zoomType','window');
			$zoomWidth = $this->params->get('windowWidth','600');
			$zoomHeight = $this->params->get('windowHeight','400');
			$args   = array('images' => $images, 'zoomType' => $zoomType , 'zoomWidth' => $zoomWidth, 'zoomHeight' => $zoomHeight);
			$html 	= $this->_render('zoom_html', $args);
			
			return array('pc-product-media-gallery' => $html);
		}
	}
	
	// as jquery is loaded after viewbeforerender so we cannot load plugin js before that
	//else it will create js error. So load plugin js on viewAfterrender.
	function onPaycartViewAfterRender($view , $task){
	
		if($view instanceof PaycartSiteBaseViewProduct && $task == 'display'){

			Rb_Html::script('plg_paycart_zoomimage/jquery.elevateZoom.min.js');
		}
	}
}