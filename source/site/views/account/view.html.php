<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );
/**
 * 
 * Order Html View
 *
 */
require_once dirname(__FILE__).'/view.php';

class PaycartSiteHtmlViewAccount extends PaycartSiteBaseViewAccount
{
	public function display($tpl = NULL)
	{	
		$juser = PaycartFactory::getUser();
		$buyer = PaycartBuyer::getInstance($juser->id);
	
		/** IMP : Order is equals to Cart **/
		$model = $this->getModel();
		
		// @PCTODO : TEMP SOLUTION
		$model->set('_query', null);
		$query = $model->getQuery();		
		$query->where('`buyer_id` = '.$juser->id);
		$query->where('`is_locked` = 1');
		$model->set('_query', $query);
		
		$model->setState('filter_order', 'locked_date');
		$model->setState('filter_order_Dir', 'DESC');		
		$carts = $model->loadRecords();		
		$totalCarts = $model->loadRecords(array(), array('limit'));
		
		/* @var $invoiceHelper PaycartHelperInvoice */ 
		$invoiceHelper = PaycartFactory::getHelper('invoice');
		$invoices = array();
		foreach($carts as $cart){
			$invoices[$cart->invoice_id] = $invoiceHelper->getInvoiceData($cart->invoice_id);
		}		
		
		$this->assign('limitstart', $model->getState('limitstart'));
		$this->assign('pagination', $model->getPagination());
		$this->assign('total_orders', count($totalCarts));
		$this->assign('invoices', $invoices);
		$this->assign('carts', $carts);
		$this->assign('buyer', $buyer);
		$this->assign('task', $this->getTask());
		return true;
	}	
	
	public function order()
	{
		$id = $this->get('order_id');
		if(!$id){
			return $this->display();
		}	
		
		$cart = PaycartCart::getInstance($id);
		$buyer = $cart->getBuyer(true);		
		
		/* @var $cartHelper PaycartHelperCart */ 
		$cartHelper = PaycartFactory::getHelper('cart');
		$productCartParticulars = $cartHelper->getCartParticularsData($id, Paycart::CART_PARTICULAR_TYPE_PRODUCT);		
		$promotionCartParticulars = $cartHelper->getCartParticularsData($id, Paycart::CART_PARTICULAR_TYPE_PROMOTION);		
		$dutiesCartParticulars = $cartHelper->getCartParticularsData($id, Paycart::CART_PARTICULAR_TYPE_DUTIES);
		$shippingCartParticulars = $cartHelper->getCartParticularsData($id, Paycart::CART_PARTICULAR_TYPE_SHIPPING);
			
		// set cart total
		$cartObject 			= (object)$cart->toArray();
		$cartObject->total 		= $cart->getTotal();
		
		// set promotion amount
		$cartObject->promotion 	= 0;
		foreach($promotionCartParticulars as $particular){
			$cartObject->promotion += $particular->total;
		}
		
		// set duties amount
		$cartObject->duties 	= 0;
		foreach($dutiesCartParticulars as $particular){
			$cartObject->duties += $particular->total;
		}
		
		// Calculate Shipments
		$shipments = $cartHelper->getShipments($id);
		$productShipments = array();
		foreach($shipments as $shipment){
			if(isset($shipment->products)){
				foreach($shipment->products as $product){
					if(!isset($productShipments[$product['product_id']])){
						$productShipments[$product['product_id']] = array();
					}
					
					$productShipments[$product['product_id']][] = array('quantity' => $product['quantity'],
																		'shipment_id' => $shipment->shipment_id);
				}
			}
		}
		
		$cartObject->subtotal 	= 0;
		$products = array();
		foreach($productCartParticulars as $particular){
			$cartObject->subtotal += $particular->total;
			$products[$particular->particular_id] = PaycartProduct::getInstance($particular->particular_id);
			
			// if shipment is not created for any product, then create a dummy record		
			if(!isset($productShipments[$particular->particular_id])){
				$productShipments[$particular->particular_id][] = array('quantity' => $particular->quantity,
																	'shipment_id' => 0);
			}
			else{
				// if all quantity of product is not consumed in shipment, then create empty reord for remaining quantity
				// but without shipment. For empty shipment $estimatedDeliveryDate will be displayed
				$quantity = 0;
				foreach($productShipments[$particular->particular_id] as $shipment){
					$quantity += $shipment['quantity'];
				}
				
				if($quantity < $particular->quantity){
					$productShipments[$particular->particular_id][] = array('quantity' => ($particular->quantity - $quantity),
																	'shipment_id' => 0);
				}
			}
		}
		
		// set shipping cost
		$cartObject->shipping = 0;
		foreach($shippingCartParticulars as $particular){
			$cartObject->shipping += $particular->total;
		}
		
		// get expeted delivery of complete cart, it will be used when no shipment is created for any item
		$estimatedDeliveryDate = null;
		foreach($shippingCartParticulars as $particular){
			$params = $particular->params;
			$date = new Rb_Date($params->delivery_date);
			if(empty($estimateDeliveryDate)){
				$estimatedDeliveryDate = $date;
				continue;
			}
			
			$estimatedDeliveryDate = ($estimateDeliveryDate->toUnix() < $date->toUnix()) ? $date : $estimatedDeliveryDate;
		}
		
		$this->assign('estimatedDeliveryDate', $estimatedDeliveryDate);
		$this->assign('shippingAddress', (object)$cart->getShippingAddress(true)->toArray());
		$this->assign('shipments', $shipments);
		$this->assign('productShipments', $productShipments);
		$this->setTpl('order');
		$this->assign('buyer', $buyer);
		$this->assign('cart', $cartObject);
		$this->assign('products', $products);
		$this->assign('invoice', (object)$cart->getInvoiceData());
		$this->assign('invoiceStatusList', PaycartFactory::getHelper('invoice')->getStatusList());
		$this->assign('productCartParticulars', $productCartParticulars);
		$this->assign('promotionCartParticulars', $promotionCartParticulars);
		$this->assign('dutiesCartParticulars', $dutiesCartParticulars);		
		$this->assign('shippingCartParticulars', $shippingCartParticulars);		
		$this->assign('task', $this->getTask());
		return true;
	}
	
	public function address($tpl = NULL)
	{	
		$juser = PaycartFactory::getUser();
		$buyer = PaycartBuyer::getInstance($juser->id);
		
		$filter = Array('buyer_id' => $juser->id, 'is_removed' => 0 );
		$addresses = PaycartFactory::getModel('buyeraddress')->loadRecords($filter);
		
		$this->assign('addresses', $addresses);
		$this->assign('buyer', $buyer);
		$this->assign('task', $this->getTask());
		$this->setTpl('address');
		return true;
	}	
	
	public function setting($tpl = NULL)
	{	
		$juser = PaycartFactory::getUser();
		$buyer = PaycartBuyer::getInstance($juser->id);
		
		$this->assign('buyer', $buyer);
		$this->assign('task', $this->getTask());
		$this->setTpl('setting');
		return true;
	}	
	
	public function login()
	{
		$this->setTpl('login');
		return true;
	}
}
