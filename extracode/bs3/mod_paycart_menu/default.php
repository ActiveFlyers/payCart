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

$categories		= PaycartAPI::getCategories();
$loggin_user 	= PaycartFactory::getUser();  

$link = isset($displayData->return_link) ? $displayData->return_link : 'index.php';
$return_link	= 	base64_encode($link);
$isMobile = PaycartFactory::getApplication()->client->mobile;
?>
<style>
	
.pc-menu{
	position :relative;
}  
 
.pc-menu-categories {
  position: static !important;
  width:100%;
}

.pc-menu-categories .pc-menu-category{
    padding: 20px 0px;
    width: 100%;
    overflow-x: auto;	
	white-space: nowrap;
}
.pc-menu-categories .pc-menu-category > li > ul {
  padding: 0;
  margin: 0;
}
.pc-menu-categories .pc-menu-category > li > ul > li {
  list-style: none;
}
.pc-menu-categories .pc-menu-category > li > ul > li > a {
  display: block;
  padding: 3px 30px;
  clear: both;
  font-weight: normal;
}
.pc-menu-categories .pc-menu-category > li ul > li > a:hover,
.pc-menu-categories .pc-menu-category > li ul > li > a:focus {
  text-decoration: none;
  color: #444;
  background-color: #f5f5f5;
}
.pc-menu-categories .pc-menu-category .dropdown-header {
  color: #428bca;
  font-size: 18px;
  font-weight:bold;
}
.pc-menu-categories .pc-menu-category form {
    margin:3px 20px;
}
.pc-menu-categories .pc-menu-category .form-group {
    margin-bottom: 3px;
}


.pc-menu .pc-menu-category > li > ul > li > a:hover,
.pc-menu .pc-menu-category > li > ul > li > a:focus {
  text-decoration: none;
  color: #444;
  background-color: #f5f5f5;  
}


.pc-menu .pc-menu-category > li > ul > li.dropdown-header {
	padding : 0px;
}
.pc-menu .pc-menu-category > li > ul > li.dropdown-header > a {
	padding: 4px 20px;
}
.pc-menu .pc-column{
	display: inline-block;
	min-height: 30px;
	vertical-align: top;
}
.pc-menu .pc-coloumn-group{
	overflow-x: auto; 
	overflow-y: hidden; 
	white-space: nowrap;
}

</style>
<div class="pc-menu">
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
       	<li class="dropdown pc-menu-categories">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo JText::_('COM_PAYCART_PRODUCTCATEGORY'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu pc-menu-category row" role="menu">
            <?php $counter = 1;?>
            <li class="">
	            <ul class="pc-column list-unstyled">
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
					     	<?php if ($counter == $itemsPerColumn): ?>
						     	</ul>	
						     	<ul class="pc-column list-unstyled">
						     	<?php $counter = 0;?>
					     	<?php endif;?>
					     <?php endif;?>
					<?php endforeach;?>
				</ul>
			</li>
          </ul>
        </li>
      </ul>
      
	<form name="pc-menu-search-form" action="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>" method="get" class="navbar-form navbar-left" role="search">
		<div class="form-group">
			<input type="text" class="form-control" placeholder="<?php echo JText::_("COM_PAYCART_SEARCH")?>" name="query"/>
		</div>
	</form>
     
    <ul class="nav navbar-nav navbar-right">
       	<li>
			<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=account&task=login&action=track');?>">
				<i class="fa fa-map-marker"> </i> <?php echo JText::_('MOD_PAYCART_MENU_TRACK_ORDER');?>
			</a>
		</li>
		<?php if (!$loggin_user->get('id')) : ?>
        <li>
            <a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=account&task=login&action=login');?>">
            <i class="fa fa-user"></i>
            <span class="hidden-phone"> 
            <?php echo JText::_('COM_PAYCART_LOGIN_AND_REGISTER'); ?>	            			
            	</span>	            		
            </a>            	
        </li>
        <?php else :?>
        <?php $display_name = $loggin_user->get('name');?>
        <li class="dropdown ">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">                			 
            	<i class="fa fa-user"></i>
               	<?php echo ucfirst($display_name); ?>
            	<span class="caret"></span>
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
            
        <li>
        	<a href='<?php echo JRoute::_('index.php?option=com_paycart&view=cart&task=display');?>'>
            	<i class="fa fa-shopping-cart"></i>
                <span class="hidden-phone"> 
                <?php echo JText::_('COM_PAYCART_CART'); ?></span>
                <span class="label label-danger pc-demo-cart-counter"></span>
            </a>
        </li>
      </ul>
    </div>
</div>
 
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
                       
	<script>
        	(function($){

        		$('.pc-menu-popover')
        				.popover()  	//pophover
        				.mouseleave(	//on mouse leave hide it
                				function() {
                    				$('.pc-menu-popover').popover('hide')
                    			});


        		var pc_menu = {};
   			 
        		pc_menu.update = 
    				{
    					onSuccess : function(response_data)
    					{
        					$('.pc-demo-cart-counter').html();
        					
		        			// 	take action
        					if ( response_data['products_count'] > 0 ) {
    						 	$('.pc-demo-cart-counter').html(response_data['products_count']);
        					}
    					},

    					onError : function(response_data)
    					{
    						console.log ( {" response contain error :  " : response_data } );
    					},
    					
    					do : function(event)
    					{
    						var request 	= [];
    						  
    			  			request['success_callback']	=	pc_menu.update.onSuccess;

    			  			request['url'] = 'index.php?option=com_paycart&view=cart&task=getProductCount&format=json';

    						paycart.request(request);
    						
    					},
    				};
    			 
    			
    			// bind event 
    			$(document).on( "onPaycartCartUpdateproduct", pc_menu.update.do);
    			
    			// on Document ready 
    			$(document).ready(function(){
    				pc_menu.update.do();
    			});
    			
        	})(paycart.jQuery);
        
        </script>

<?php 
