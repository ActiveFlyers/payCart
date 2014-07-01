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
		self::_addScript();
		
		$html = parent::getInput();
		// add new category option should be required or not			
		$addNew = $this->element['addnew'] ? (string) $this->element['addnew'] : false;
		
		if($addNew == 'true') {
			$html = "
					{$html}
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
		$category = self::getCategory();		
		return PaycartHtml::buildOptions($category);		
	}
	
	private function _addScript()
	{
		$result = self::getCategory();
		
		$category 	= Array();
		foreach ($result as $categoryId => $value) {
			$category[$categoryId] = "'$value->title'";
		}
		
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
				$('.paycart_category_class').val(response.productcategory_id);
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
						// check value is not empty				
						if(!value) {
							alert(rb.cms.text._('COM_PAYCART_JS_CATEGORY_NAME_REQUIRED', 'Category name should be required'));
							$('#add_new_category').focus();
							return false;
						}
						// get All available options				
						var options = [<?php echo implode(",", $category); ?>];
						
						if(-1 != $.inArray(value, options)) {
							alert(rb.cms.text._('COM_PAYCART_JS_CATEGORY_NAME_ALREADY_EXIST', 'Category name already available'));
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
		
	 /**
	 * @return all available category array('category_id'=>'Array of category stuff')
	 */
	private static function getCategory($reset = false)
	{
		static $result ;
		if ($result && !$reset ) {
			return $result;
		}
		
		$model = PaycartFactory::getModel('productcategory');
		// Should be sorted according to 'title' so need to write query with "order by"
		$model->clearQuery();  
		$query = $model->getQuery()->clear('order')->order('title');
		
		$result = $model->loadRecords();
		 
		return $result;
	}
}