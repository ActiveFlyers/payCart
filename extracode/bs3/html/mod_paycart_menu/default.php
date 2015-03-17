<?php

/**
 * @copyright   Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Layouts
 * @contact	support+paycart@readybytes.in
 * @author 	Manish Trivedi  
 */

/**
 * List of Populated Variables
 * $displayData = have all required data 
 * $displayData->return_link		// link after logout 
 * 
 */

// no direct access
defined('_JEXEC') or die;

Rb_HelperTemplate::loadSetupEnv();

// load bootsrap, font-awesome
$config = PaycartFactory::getConfig();
$load = array('jquery', 'rb', 'font-awesome');
if($config->get('template_load_bootstrap', false)){
	$load[] = 'bootstrap';
}
Rb_HelperTemplate::loadMedia($load);

Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/paycart.js');
Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/paycart.css');

Rb_Html::script('mod_paycart_menu/script.js');
Rb_Html::stylesheet('mod_paycart_menu/style.css', array());

?>
<style>
.pc-mod-menu ul.nav > li{
	display : inline-block;
}
.pc-mod-menu .pc-menu-categories > li > ul > li.dropdown-header > a{
	padding: 4px 0px;
}
</style>
<nav class="navbar navbar-default pc-mod-menu pc-mod-menu<?php echo $class_sfx;?> ">
	<div class="navbar-collapse clearfix">
			<!-- Product Category Menu 	-->
        	<ul class="nav text-center <?php echo !$isMobile ? 'navbar-nav pull-left' : '';?>">
				<!-- Product Category link on desktop, tab etc -->
             	<li class="dropdown pc-menu-categories-dropdown">             
                	<a class="dropdown-toggle hidden-xs" data-toggle="dropdown"  href="#"><span><?php echo JText::_('COM_PAYCART_PRODUCTCATEGORY'); ?> <b class="caret"></b></span></a>
                	<a href="javascript:void(0);" data-toggle="offcanvas" data-target="#allcategories" class="visible-xs"><span class="text-white fa fa-bars"></span></a>  
                	<ul class="dropdown-menu pc-menu-categories hidden-xs">
                  		<!-- get ctaegory links -->
                  		<li>
                        	<ul class="pc-column unstyled">
                  				<?php $counter = 1;?>
                  				<li class="dropdown-header">
                  					<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>">
										<?php echo JText::_('COM_PAYCART_All') .' '.JText::_('COM_PAYCART_PRODUCTCATEGORY'); ?>
		            				</a>
                  				</li>
								<?php foreach( $categories as $cat): ?>
				     				<?php if (!$cat->level == 0 && $cat->level <= 2):?>
				     					<?php if ($counter == $itemsPerColumn): ?>
				     						</ul>
				     						<ul class="pc-column unstyled">
				     			 			<?php $counter = 0;?>
				     					<?php endif;?>	
				     					<li class="<?php if ($cat->level == 1) { echo 'dropdown-header'; };?>">
				     						<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display&productcategory_id='.$cat->productcategory_id);?>" class="<?php echo 'pc-level'.$cat->level;?>">
				     							<?php echo $cat->title;?>
				     						</a>
				     					</li>
				     					<?php $counter++;?>				     					
				     				<?php endif;?>
				     			<?php endforeach;?>
                			</ul>
                		</li>
                	</ul>
            	</li>
            	            
            	<li>            	
            		<a href="javascript:void(0);" class="text-white visible-xs" data-toggle="offcanvas" data-target="#searchbar"><i class="fa fa-search"> </i></a>
            		<form name="pc-menu-search-form" action="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>" method="get" class="navbar-form hidden-xs" role="search">
						<div class="form-group">
						<input type="text" class="form-control" placeholder="<?php echo JText::_("COM_PAYCART_SEARCH")?>" name="query"/>
						</div>
					</form>
				</li>
			<?php if(!$isMobile):?>	
            	</ul>
            	<ul class="nav navbar-right <?php echo !$isMobile ? 'navbar-nav' : '';?>">
            <?php endif;?>
           		<li class="hidden-xs">
					<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=account&task=login&action=track');?>">
				   		<i class="fa fa-map-marker"> </i> <span class="hidden-xs"><?php echo JText::_('MOD_PAYCART_MENU_TRACK_ORDER');?></span>
				   	</a>
				</li>
			
           		<?php if (!$loggin_user->get('id')) : ?>
	           	<li>
	            	<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=account&task=login&action=login');?>">
	            		<i class="fa fa-user"></i>
	            		<span class="hidden-xs"> 
	            			<?php echo JText::_('COM_PAYCART_LOGIN_AND_REGISTER'); ?>	            			
	            		</span>	            		
	            	</a>            		            	
	            </li>
            	<?php else :?>
            		<?php $display_name = $loggin_user->get('name');?>
               		<li class="dropdown ">
            	    	<a class="dropdown-toggle " data-toggle="dropdown"  href="#" aria-expanded="false">                			
					        <span><i class="fa fa-user"></i><span class="hidden-xs"> <?php echo ucfirst($display_name); ?></span><b class="caret"></b></span> 
                		</a>                
                		
               			<ul class="dropdown-menu " role="menu">
                  			<!-- Users links -->                    
                    		<li><a href="javascript::void();"><?php echo JText::_('MOD_PAYCART_MENU_HI');?>! <?php echo ucfirst($display_name); ?></a></li>
                    		<li class="divider"></li>           
                    		<li>
                        		<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=order');?>"> 
                        			<i class="fa fa-tags"> </i> <?php echo JText::_('COM_PAYCART_MY_ORDERS'); ?>
                        		</a>
	                       	</li>                       
    	                   	<li>
        	                	<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=address');?>"> 
            	            		<i class="fa fa-home"> </i> <?php echo JText::_('COM_PAYCART_MANAGE_ADDRESS'); ?>
                	        	</a>
                    	   	</li>
                       		<li>
                        		<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=setting');?>"> 
                        			<i class="fa fa-user"> </i> <?php echo JText::_('COM_PAYCART_ACCOUNT_SETTINGS'); ?>
	                        	</a>
    	                   	</li>
        	               	<li class="divider"></li>
            	           	<li>
                       			<a href="#" onclick='pc_menu_logout(); return false;' >
                       				<i class="fa fa-sign-out"> </i> <?php echo JText::_('JLOGOUT'); ?>
                       			</a>
                       		</li>
                		</ul>
                	</li>	
            <?php endif;?>
            
             <li><a href='<?php echo JRoute::_('index.php?option=com_paycart&view=cart&task=display');?>'>
                <i class="fa fa-shopping-cart"></i>
                <span class="hidden-xs"> 
                <?php echo JText::_('COM_PAYCART_CART'); ?></span>
                <span class="badge badge-info pc-demo-cart-counter"></span>
                </a>
            </li>
        </ul>         
    </div>	

	<div class="" id="pc-mob-offcanvas">
		<div class="sidebar-offcanvas" role="navigation" id="allcategories">
			<div class="sidebar-offcanvas-inner">
				<span class="close" data-toggle="offcanvas" data-target="#allcategories"><i class="fa fa-times fa-2x"></i></span>
				<ul class="pc-menu-categories">
                  	<!-- get ctaegory links -->
                  	<li>
                        <ul class="pc-column unstyled">
                  			<?php $counter = 1;?>
                  			<li class="dropdown-header">
                  				<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>">
									<?php echo JText::_('COM_PAYCART_All') .' '.JText::_('COM_PAYCART_PRODUCTCATEGORY'); ?>
	            				</a>
                  			</li>
							<?php foreach( $categories as $cat): ?>
			     				<?php if (!$cat->level == 0 && $cat->level <= 2):?>			     						
			     					<li class="<?php if ($cat->level == 1) { echo 'dropdown-header'; };?>">
			     						<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display&productcategory_id='.$cat->productcategory_id);?>" class="<?php echo 'pc-level'.$cat->level;?>">
			     							<?php echo $cat->title;?>
			     						</a>
			     					</li>			     					
			     					<?php $counter++;?>
			     				<?php endif;?>
			     			<?php endforeach;?>
                		</ul>
                	</li>
                </ul>
			</div>
		</div>
	
		<div class="sidebar-offcanvas" id="searchbar" role="navigation">
			<div class="sidebar-offcanvas-inner container-fluid">
				<span class="close" data-toggle="offcanvas" data-target="#searchbar"><i class="fa fa-times fa-2x"></i></span>
				<div class="pc-menu-searchbox text-center">				
					<form class="form-search form-inline" name="pc-menu-search-form" action="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>" method="get">
						<div class="form-group">						
							<input type="text" class="form-control" placeholder="<?php echo JText::_("COM_PAYCART_SEARCH")?>" name="query"/>							
						</div>
						<button type="submit" class="btn"><?php echo JText::_('COM_PAYCART_SEARCH');?></button>
					</form>					
				</div>
			</div>
		</div>
     </div>
</nav>
 
<div class ="hide">
	<script>
		var pc_menu_logout =function()
		{
			document.getElementById("pc-demo-logout-form").submit();
			return false;
		};
	</script>
					         
	<form action="<?php echo 'index.php?option=com_users'; ?>" method="post" id="pc-demo-logout-form" name="pc-demo-logout-form"  class="hide">
		<div>
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="user.logout" />
			<input type="hidden" name="return" value="<?php echo $return_link; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>


<?php 
