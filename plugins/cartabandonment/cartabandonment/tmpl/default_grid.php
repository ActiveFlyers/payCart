<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		rimjhim
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

JHtml::_('behavior.formvalidation');
echo $this->loadTemplate('js');
?>

<!-- LANGUAGE SWITCHER -->
<?php 
	if(PAYCART_MULTILINGUAL){
		$lang_code = PaycartFactory::getPCCurrentLanguageCode();
		$flag = '<span class="pull-left pc-language">'.PaycartHtmlLanguageflag::getFlag($lang_code).' &nbsp; '.'</span>';
	}
	else{
		$flag = '';
	}
?>
<div class="row-fluid">
	<!-- ADMIN MENU -->
	<div class="span2">
		<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=cartabandonment'); 
		?>
	</div>
	<div class="span10">
		<!--  LANGUAGE SWITCHER --> 
		<?php		
		if(PAYCART_MULTILINGUAL){
			$displayData = new stdClass();
			$displayData->uri  = $uri;
			echo Rb_HelperTemplate::renderLayout('paycart_language_switcher', $displayData);
		}		
		?>
		
		<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">		
			<table class="table table-striped ">					
				<!-- TABLE HEADER START -->
                <thead>
                	<tr>
                		<th width="1%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
                		<th class="hidden-phone"><?php echo PaycartHtml::_('grid.sort', 'COM_PAYCART_ADMIN_ID', 'cartabandonment_id', $filter_order_Dir, $filter_order);?></th>
                		<th><?php echo PaycartHtml::_('grid.sort', 'COM_PAYCART_ADMIN_TITLE', 'title', $filter_order_Dir, $filter_order);?></th>
                		<th><?php echo Rb_Html::_('grid.sort', "COM_PAYCART_ADMIN_STATUS", 'published', $filter_order_Dir, $filter_order);?></th>					</tr>
				<!-- TABLE HEADER END -->
                </thead>
					
				<tbody>
				<!-- TABLE BODY START -->
					<?php $count= $limitstart;
					$cbCount = 0;
					foreach ($records as $record):?>
						<tr class="<?php echo "row".$count%2; ?>">								
							<th><?php echo PaycartHtml::_('grid.id', $cbCount, $record->cartabandonment_id ); ?></th>				
							<td class="hidden-phone"> <?php echo $record->cartabandonment_id;?></td>
							<td>
								<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cartabandonment&task=edit&cartabandonment_id='.$record->cartabandonment_id)?>" class="edit-abandonment" >
                                	<?php  echo $record->title   ?>
                                </a>
                            </td>
                            <td><?php echo PaycartHtml::_("rb_html.boolean.grid", $record, 'published', $cbCount, 'tick.png', 'publish_x.png', '', $langPrefix='COM_PAYCART');?></td>
                        </tr>
					<?php $count++;?>
					<?php $cbCount++;?>
					<?php endforeach;?>
				<!-- TABLE BODY END -->
				</tbody>
					
				<tfoot>
					<tr>
						<td colspan="5"><?php echo $pagination->getListFooter(); ?></td>
					</tr>
				</tfoot>
			</table>
			
			<input type="hidden" name="filter_order" value="<?php echo $filter_order;?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir;?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>			
	</div>
</div>
<?php 