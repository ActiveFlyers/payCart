<?php
/**
 *@copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 *@license		GNU/GPL, see LICENSE.php
 *@package		PayCart
 *@subpackage	Pacart Form
 *@author 		mManishTrivedi 
*/

defined('JPATH_PLATFORM') or die;

/**
 * 
 * Define here all required Paycart internal events/triggers
 *
 * @author Manish Trivedi
 *
 */
class PaycartEvent extends JEvent
{
	/**
	 * Invoke this method on every save task of all entity 
	 * @param Rb_Lib $previousObject, previous lib object (Beofre save)
	 * @param RB_Lib $currentObject , Current Lib object (After save )
	 * @param string $entity, entity name. Save task call on this entity. 
	 */
	public function onPaycartAfterSave($previousObject, $currentObject, $entity)
	{
		switch ($entity) {
			case 'product' :
				self::_onProductAfterSave($previousObject, $currentObject);
				break;
		}
	}

	/**
	 * 
	 * Method invoke when Product will be save 
	 * @param Product_Lib $previousObject, Before save
	 * @param Product_Lib $currentObject, After save
	 */
	protected static function _onProductAfterSave($previousObject, $currentObject) 
	{
		return true;
	}
	

}

/**
 * Event Registeration here 
 */
//$dispatcher = JDispatcher::getInstance();
//$dispatcher->register('onPaycartAfterSave', 'PaycartEvent');
