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
						<input class='' type='text' id='add_new_category' placeholder='Add category name'>
						<button class='btn' type='button' id='add_new_category_button'>".
								Rb_Text::_('COM_PAYCART_ADD_NEW_PRODUCT_CATEGORY')."
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
		$category = PaycartHelperCategory::getCategory();

		$listLabel = parent::getOptions();
		if($listLabel) {
			$listLabel[0]->title = $listLabel[0]->text;
			$category = array_merge($listLabel, $category);
		}
		
		return PaycartHtml::buildOptions($category);		
	}
	
	private function _addScript()
	{
		$result = PaycartHelperCategory::getCategory();
		
		$category 	= Array();
		foreach ($result as $categoryId => $value) {
			$category[$categoryId] = "'$value->title'";
		}
		
		ob_start();
		?>
		paycart.jQuery(document).ready(function($)
		{
			<!-- Callback function when category successfully added				-->
			var callbackOnSuccess = function(response)
			{
				// PCTODO :: add new added list into category list
				alert('added successfully.// PCTODO :: add new added list into category list');
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
						paycart.category.add(value, callbackOnSuccess, callbackOnError);
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