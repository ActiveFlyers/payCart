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
 * input field for Images
 *
 * @package     Paycart
 * @subpackage  Form
 */
class PaycartFormFieldImage extends JFormField
{

	/**
	 * The form field type.
	 * @var    string
	 */
	public $type = 'Image';	
		
	/**
	 * Method to get the field input markup for the Image field.
	 * Field attributes allow specification of a maximum file size and a string
	 * of accepted file extensions.
	 *
	 * @return  string  The field input markup.
	 *
	 * @note    The field does not include an upload mechanism.
	 * @see     JFormFieldMedia
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		// default all images accept. {'image/x-png, image/gif, image/jpeg, image/bmp, image/ico, image/psd, image/eps}
		$accept 	= 	' accept="' .($this->element['accept'] ?  (string) $this->element['accept'] . '"' : 'image/*"');
		$size 		= 	$this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$class 		= 	$this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$disabled 	= 	((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$required 	= 	$this->required ? ' required="required" aria-required="true"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
		
		//PCTODO:: Display image if exist
		return '<input type="file" name="' . $this->name . '" id="' . $this->id . '" value=""' . $accept . $disabled . $class . $size
			. $onchange . $required . ' />';
	}
}
