<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		paycart
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PaycartFormFieldTimer extends JFormField
{
	function getInput()
	{
		$name   = $this->name;
		$value  = $this->value;
		$prefix = $this->group.$this->fieldname;
		
		// $prefix = $control_name.$name;
		$class = Rb_HelperUtils::jsCompatibleId($prefix,'_');
		
		$yearHtml = '<span >'.JText::_('COM_PAYCART_TIMER_YEARS').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_year" id="'.$class.'_year" >';
		$monthHtml= '<span >'.JText::_('COM_PAYCART_TIMER_MONTHS').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_month" id="'.$class.'_month" >';
		$dayHtml  = '<span >'.JText::_('COM_PAYCART_TIMER_DAYS').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_day" id="'.$class.'_day" >';
		$hourHtml = '<span >'.JText::_('COM_PAYCART_TIMER_HOURS').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_hour" id="'.$class.'_hour" >';
		$minHtml  = '<span >'.JText::_('COM_PAYCART_TIMER_MINUTES').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_minute" id="'.$class.'_minute" >';
		$secHtml  = '<span>'.JText::_('COM_PAYCART_TIMER_SECONDS').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_second" id="'.$class.'_second" >';		 
			
		for($count=0 ; $count<=60 ; $count++){
			$yearHtml  .= ($count<=10) ? '<option value="'.$count.'">'.$count.'</option>' : '';
			$monthHtml .= ($count<=11) ? '<option value="'.$count.'">'.$count.'</option>' : '';
			$dayHtml   .= ($count<=30) ? '<option value="'.$count.'">'.$count.'</option>' : '';
			$hourHtml  .= ($count<=23) ? '<option value="'.$count.'">'.$count.'</option>' : '';
			$minHtml   .= ($count<=59) ? '<option value="'.$count.'">'.$count.'</option>' : '';
			$secHtml   .= ($count<=59) ? '<option value="'.$count.'">'.$count.'</option>' : '';
		}

		$yearHtml  .= '</select> ';
		$monthHtml .= '</select> ';
		$dayHtml   .= '</select> ';
		$hourHtml  .= '</select> ';
		$minHtml   .= '</select> ';
		$secHtml   .= '</select> ';

		$text = '<span id="'.$class.'" >&nbsp;</span>';
		
		$tempHtml  = '';
		$tempHtml .= ((string)$this->element['year'])?$yearHtml:'';
		$tempHtml .= ((string)$this->element['month'])?$monthHtml:'';
		$tempHtml .= ((string)$this->element['day'])?$dayHtml:'';
		$tempHtml .= ((string)$this->element['hour'])?$hourHtml:'';
		

		//IMP : due to js incorrect calculation NaN gets set in the value 
		// which ultimately results incorrect time so NaN is replaced with default value
		$value = str_replace('NaN', '00', $value);
		$hidden = '<input type="hidden" id="'.$class.'" name="'.$name.'" value="'.$value.'" />';
		
		self::_setupTimerScript();
		
		ob_start();
		?>
		paycart.jQuery(document).ready(function(){
			paycart.element.timer.setup('<?php echo $class;?>', '<?php echo $value;?>');			
		});
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		PaycartFactory::getDocument()->addScriptDeclaration($content);

		//$micro = '<span class="hide">'.$hourHtml.$minHtml.$secHtml.'</span>';
//		if(PaycartFactory::getConfig()->microsubscription){
//			$micro = $hourHtml.$minHtml.'<span class="hide">'.$secHtml.'</span>';
//		}
		
		return 	'<div id="timer-warp-'.$class.'">
					<div class="readable">
						<span class="pc-content"></span>
					</div>
					<div class="editable">'
						. $tempHtml
						. $hidden.
					'</div>
				</div>';
	}

	static function _setupTimerScript()
	{
		static $added = false;
		if($added){
			return true;
		}
		
		ob_start();
		?>

		(function($){
			// if elements not defined already
			if(typeof(paycart.element)=='undefined'){
				paycart.element = {};
			}
	
			paycart.element.timer = {
				elems : Array('year', 'month', 'day', 'hour', 'minute', 'second'),
			
				// get value from selects and combine into string
				getValue : function(elem_class){
					var prefix	= elem_class+'_';
	        		var timer 	= '';
				
	 				for(var i =0; i < this.elems.length ; i++){
	 					var value= parseInt($('#' + prefix + this.elems[i]).val());
	 					if(10 > value){
							value = '0' + value;
						}
	
						timer += value;
					}
					
					return timer;
				},
				
				// set given string to different timer selects
				setValue : function(elem_class, value){
					var prefix	= elem_class+'_';
					if(value == null || value.trim().length <=0){
						value = '000000000000';
					}
					 
					value = value.replace(/NaN/g, "00"); 
		 			for(var i =0; i < this.elems.length ; i++){
		 				$('#' + prefix+ this.elems[i]).val(parseInt(value.substr(i*2, 2), 10));
		 			}
				},
				
				
				format : function(elem_class){
					var prefix	= elem_class+'_';
					
					var data = {timer:{}};
	 				for(var i =0; i < this.elems.length ; i++){
	 					var value = $('#' + prefix + this.elems[i]).val();
	 					if(typeof(value) == 'undefined' || value == null){
	 						value = 0;
	 					}
	 					data.timer[this.elems[i]]=parseInt(value);
					}
					
					//data['domObject'] = 'timer-warp-'+elem_class+' .readable span.pc-content';
					//var url='index.php?option=com_paycart&view=notification&task=format&object=timer&headerFooter=0';
					//paycart.ajax.go(url, data);
				},
			
				onchange : function(elem_class){
					$('#' + elem_class).attr('value', this.getValue(elem_class));
					this.format(elem_class);
				},
				
				setup : function(elem_class, value){
	
		 			this.setValue(elem_class, value);
		 				
					//update select choosen
					$('select').trigger("liszt:updated");	   		
				
			   		// show readble
			   		this.format(elem_class);
			   		
					
					var hoverClass='pp-mouse-hover';    		        
			        $('#timer-warp-'+elem_class).parent().hover(
			    		function(){$(this).addClass(hoverClass);},
			    		function(){$(this).removeClass(hoverClass);}
			        );
			        
			        //setup ppeditable
			        //$('#timer-warp-'+elem_class+' .editable').hide();
					//$('#timer-warp-'+elem_class+' .readable').click(function(){
					//		$('#timer-warp-'+elem_class+' .editable').fadeToggle(200);
					//	});
					
					// setup onchange functionality
		    		$('select.'+elem_class).live('change' , function(){ 
		    				paycart.element.timer.onchange(elem_class);
		    			});  
				}
			}
		})(paycart.jQuery);

		<?php
		$content = ob_get_contents();
		ob_end_clean();
		
		PaycartFactory::getDocument()->addScriptDeclaration($content);
		$added = true;
		return true;
	}
}