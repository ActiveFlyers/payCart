<?php
/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayCart
* @subpackage	backend
* @contact 		support+paycart@readybytes.in
* @author 		neelam soni
*/
defined('_JEXEC') or die();
?>
<div class="pc-buyer-wrapper clearfix">
<div class="pc-buyer row-fluid">

<!-- CONTENT START -->

<!-- ADMIN MENU -->
<div class="span2">
	<?php
			$helper = PaycartFactory::getHelper('adminmenu');			
			echo $helper->render('index.php?option=com_paycart&view=setupchecklist'); 
	?>
</div>
<!-- ADMIN MENU -->

<div class="span10">
<form action="<?php echo $uri; ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="span7 pc-setUpChecklist">
				<div class="row-fluid pc-setUpChecklist-header">
					<div class="span1">
						<h2><i class="fa fa-check-square-o fa-lg text-info"></i></h2>
					</div>
					<div class="span11">
						<h2 class="text-info"> <?php echo JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_ESSENTIAL_SETTINGS");?></h2>
						<?php if(empty($error_rules)){?>
							<p class="text-success"><?php echo JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_ESSENTIAL_SETTINGS_SUCCESS");?></p>
						<?php }else{?>
							<p class="text-warning"><?php echo JText::_("COM_PAYCART_ADMIN_SETUP_CHECKLIST_ESSENTIAL_SETTINGS_DESC");}?></p>
						<hr/>
					</div>				
				</div>
				
				<?php 
					$i =1;
					if(isset($error_rules))
					{
						foreach ($error_rules as $error_rule)
						{?>
							<div class="row-fluid">
								<div class="span1">
									<i class="fa fa-remove fa-lg text-error"></i>
								</div>
								
								<div class="span11">
						  			<a href="#" data-toggle="collapse" data-target="#helpMsg_rule_<?php echo $i;?>">
										<strong><span class="pc-margin-left"><?php echo $error_rule['desc'];?></span></strong>
									</a>									
										
									<div id="helpMsg_rule_<?php echo $i;?>" class="collapse">
									    <p class="pc-setchecklist-helpMsg"><?php echo $error_rule['helpMsg'];?></p>
									    <hr/>
									</div>
									
									<?php if(array_key_exists('info', $error_rule) && isset($error_rule['info'])){?>
											<i class="fa fa-info-circle text-info"></i>
											<span>&nbsp;</span>
											<strong><span class="muted"><?php echo $error_rule['info'];}?></span></strong>
						  		</div>
							</div>
				  <?php $i++;
						}
					}
					
					if(isset($info_rules))
					{
						foreach ($info_rules as $info_rule)
						{?>
							<div class="row-fluid">
								<div class="span1">
									<i class="fa fa-info-circle fa-lg text-info"></i>
								</div>
								
								<div class="span11">
						  			<a href="#" data-toggle="collapse" data-target="#helpMsg_rule_<?php echo $i;?>">
										<strong><span class="pc-margin-left"><?php echo $info_rule['desc'];?></span></strong>
									</a>									
										
									<div id="helpMsg_rule_<?php echo $i;?>" class="collapse">
									    <p class="pc-setchecklist-helpMsg"><?php echo $info_rule['helpMsg'];?></p>
									    <hr/>
									</div>
						  		</div>
							</div>
				  <?php $i++; 
						}
					}
					
					if(isset($success_rules))
					{
						foreach ($success_rules as $success_rule)
						{?>
							<div class="row-fluid">
								<div class="span1">
									<i class="fa fa-check fa-lg text-success"></i>
								</div>
								
								<div class="span11">
						  			<a href="#" data-toggle="collapse" data-target="#helpMsg_rule_<?php echo $i;?>">
										<strong><span class="pc-margin-left"><?php echo $success_rule['desc'];?></span></strong>
									</a>									
										
									<div id="helpMsg_rule_<?php echo $i;?>" class="collapse">
									    <p class="pc-setchecklist-helpMsg"><?php echo $success_rule['helpMsg'];?></p>
									    <hr/>
									</div>
									
									<?php if(array_key_exists('info', $success_rule) && isset($success_rule['info'])){?>
											<i class="fa fa-info-circle text-info"></i>
											<span>&nbsp;</span>
											<strong><span class="muted"><?php echo $success_rule['info'];}?></span></strong>
						  		</div>
							</div>
				  <?php $i++;
						}
					}
				?>
		</div>
		
		<div class="span5">
		
		</div>
	</div>
</form>
</div>
<!-- CONTENT END -->

</div>
</div>
<?php 