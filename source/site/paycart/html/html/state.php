<?php
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayCart
* @subpackage	Backend
* @author 		mManishTrivedi 
*/

// no direct access
use SeleniumClient\SelectElement;
defined( '_JEXEC' ) or die( 'Restricted access' );



class PaycartHtmlState extends Rb_Html
{	
	/**
	 * 
	 * Invoke to get Paycart State html
	 * @param $country_selector		:	State field depends on this country field
	 * @param $name					:	Field name
	 * @param $value				:	Field Value
	 * @param $attr					:	Field attribute
	 */
	public static function getList($country_selector, $name, $value, $attr = Array())
	{
		$options = Array();
		
		$html	=	PaycartHtml::_('select.genericlist', $options, $name, $attr, 'state_id', 'title', $value);
		
		$state_selector	=	"\"[name='$name']\"";
		
		$script	=	self::_addScript($country_selector, $state_selector);
		
		return $html.$script;
		
	}
	
	private static function _addScript($country_selector, $state_selector) 
	{
		?>
		
		<script>

			(function($){

				$(<?php echo $country_selector ?>).on('change',  function() {
					paycart.state.onCountryChange(<?php echo $country_selector ?>, <?php echo $state_selector ?>);
				});

				paycart.state.onCountryChange(<?php echo $country_selector ?>, <?php echo $state_selector ?>);
				
			})(paycart.jQuery);
			
		</script>
		<?php 
	}
	
	
}