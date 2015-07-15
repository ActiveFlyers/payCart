<?php

/**
* @copyright        Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license           GNU/GPL, see LICENSE.php
* @package           Joomla.Plugin
* @subpackage        Paycart
* @contact                support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @author Garima Agal
*/
class PlgPaycartZoomimages extends RB_Plugin
{
	function onPaycartImageBeforeLoad($product)
	{
		
		if(PaycartFactory::getApplication()->client->mobile){
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
		
		return $html;
	}
}