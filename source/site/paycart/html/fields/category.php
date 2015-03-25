<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	Backend
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');

//load Paycart stuff (required when anybody use this element without paycart page like on menu-creation)
include_once JPATH_SITE.'/components/com_paycart/paycart/includes.php';
 
/**
 * Custom Element for paycart product category.
 * Type = paycart.productcategory
 * addnew
 * @author manish
 *
 */

class PaycartFormFieldCategory extends JFormFieldList
{	
	public function getInput()
	{
		$html = parent::getInput();
		// add new category option should be required or not			
		$addNew = $this->element['addnew'] ? (string) $this->element['addnew'] : false;
		
		if($addNew == 'true') {
			
			self::_addScript();
			
			$html = "
					<div>{$html}</div>
					<br/>
					<div class='input-append'>
						<input class='' type='text' id='add_new_category' placeholder='".JText::_('COM_PAYCART_ADMIN_CATEGORY_ETER_NEW_TITLE')."'>
						<button class='btn' type='button' id='add_new_category_button'>".
								Rb_Text::_('COM_PAYCART_ADMIN_ADD')."
						</button>
					</div>";
		}
		
		return $html;
	}
	
	/**
	 * Return all availble category options
	 * @see libraries/joomla/form/fields/JFormFieldList::getOptions()
	 */
	public function getOptions()
	{
		$options = parent::getOptions();
		$category = PaycartFactory::getHelper('productcategory')->getCategory();
		 
		// remove root category
		if ( isset($this->element['show_root']) && !(int)$this->element['show_root'] ) {
			unset($category[Paycart::PRODUCTCATEGORY_ROOT_ID]);
		} 

		foreach ($category as $key => $cat){
			$category[$key]->title = str_repeat('&mdash;', ($cat->level - 1)<0?0:($cat->level - 1)).' '.$cat->title;
		}
		
		return array_merge($options, PaycartHtml::buildOptions($category));		
	}
	
	private function _addScript()
	{
		ob_start();
		?>
		paycart.jQuery(document).ready(function($)
		{
			<!-- Callback function when category successfully added				-->
			var callbackOnSuccess = function(data)
			{
				var response = data[0][1];
				var option = $('<option/>');
				option.attr({ 'value': response.productcategory_id }).text(response.title);
				//append new oprion to select list
				$('.paycart_category_class').append(option);
				// default selected
				$('.paycart_category_class').val(response.productcategory_id).trigger("liszt:updated");
			};
			<!-- Callback function when error occur during category adding operation	-->
			var callbackOnError = function ()
			{
				// PCTODO :: Proper Error-handling 
				alert('error');
			};
			
			
			$('#add_new_category_button').click( function()
					{
						var value = $('#add_new_category').val();
						value = value.trim();
						// check value is not empty				
						if(!value) {
							alert('<?php echo JText::_('COM_PAYCART_ADMIN_CATEGORY_NAME_REQUIRED');?>');
							$('#add_new_category').focus();
							return false;
						}

						paycart.admin.product.category.add(value, callbackOnSuccess, callbackOnError);
					}
				);		
	
		});
		
		<?php
		$script = ob_get_contents();
		ob_end_clean();
		JFactory::getDocument()->addScriptDeclaration($script); 
		;
	}
}