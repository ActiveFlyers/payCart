<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		Puneet Singhal, Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Paymentgateway Lib
 * @author manish
 *
 */
class PaycartPaymentgateway extends PaycartLib
{
	/**
	 * Payment Gateway table fields
	 */
	protected $paymentgateway_id;
	protected $published;
	protected $processor_type;
	protected $processor_config;
	protected $title;
	protected $description;
	protected $paymentgateway_lang_id;
	protected $lang_code;
	
	/**
	 * 
	 * PaycartPaymentgateway Instance
	 * @param  $id,			existing Paymentgateway id
	 * @param  $bindData, 	required data to bind on return instance	
	 * @param  $dummy1, 	Just follow code-standards
	 * @param  $dummy2, 	Just follow code-standards
	 * 
	 * @return PaycartPaymentgateway lib instance
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('paymentgateway', $id, $bindData);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/_rbsl/rb/rb/Rb_Lib::reset()
	 */
	public function reset() 
	{		
		$this->paymentgateway_id		=	0;
		$this->title					=	'';
		$this->description				=	'';
		$this->paymentgateway_lang_id	=	0;
		$this->lang_code				=	PaycartFactory::getCurrentLanguageCode();
		$this->published				=	1;
		$this->processor_type			=	'';
		$this->processor_config			=	new Rb_Registry();
		
		return $this;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getType()
	{
		return $this->processor_type;
	}
	
	public function getConfig()
	{
		return $this->processor_config;
	}
	
	/**
	* Invoke to get Processor configuration html
	*/
	public function getConfigHtml($type)
	{
		try {
			$html	=	'';
			$xml_file 	= 	PaycartFactory::getHelper('invoice')->getProcessorConfigFile($type);		
			
			$form = $this->getModelform()->getForm();
			$form->loadFile($xml_file, true, '//config');
			
			// bind data on form
			$form->bind($this->toArray());
			
			ob_start();
			
			// Put here help msg, if needed
			foreach ($form->getFieldsets('processor_config') as $name => $fieldSet) {
				foreach ($form->getFieldset($name) as $field) {
				?>
					<div class="control-group">
						<div class="control-label">	<?php echo $field->label; ?> </div>
						<div class="controls">		<?php echo $field->input; ?> </div>
					</div>
				<?php 
				}
			}
			?>
			<script>
				paycart.radio.init();
			</script>
			<?php 
			
			
			$html = ob_get_contents();
			ob_clean();
			
			
		} catch (Exception $ex ) {
			PaycartFactory::getHelper('log')->add($ex->getMessage());
		}
		
		return $html;
	}

	
}
