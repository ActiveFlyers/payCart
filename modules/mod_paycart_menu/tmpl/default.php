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
	/* styles for Tab browsers smaller than 1280px;  */
	@media only screen and (max-width:1280px){       
     	.mob-nav{padding:0;}
    }
	/* styles for mobile browsers smaller than 480px; (iPhone) */
	@media only screen and (max-width:320px){       
     	.mob-nav{padding:0;}
    }
    @media only screen and (max-width:480px){
	     .mob-nav > ul.nav:first-child{margin-right:0;}
    }
    
<?php if(!$isMobile):?>
.pc-menu .pc-menu-categories .dropup, 
.pc-menu .pc-menu-categories .dropdown,
.pc-menu .pc-menu-categories.nav, 
.pc-menu .pc-menu-categories .collapse {
    position: static;
}
.pc-menu .navbar-inner{
    position: relative;
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

.pc-menu .pc-menu-category > li > ul {
  padding: 0;
  margin: 0;
}
<?php endif;?>

.pc-menu .navbar-nav>li>.pc-menu-category {
    margin-top:20px;
    border-top-left-radius:4px;
    border-top-right-radius:4px;
}

.pc-menu .navbar-default .navbar-nav>li>a {
    width:200px;
    font-weight:bold;
}

.pc-menu .pc-menu-category > li > ul > li {
  list-style: none;
}

.pc-menu .pc-menu-category > li > ul > li > a {
	display: block;
	padding: 2px 30px;
	clear: both;
	font-weight: normal;
	line-height: 1.428571429;
	color: rgb(153, 153, 153);
	white-space: normal;
}

.pc-menu .pc-menu-category > li > ul > li.dropdown-header > a {
	padding: 4px 20px;
}

.pc-menu .pc-menu-category {
    padding: 20px 0px;
    width: 100%;
    box-shadow: none;
    -webkit-box-shadow: none;
    overflow-x: auto;	
	white-space: nowrap;
}

.pc-menu .pc-menu-category > li > ul > li > a:hover,
.pc-menu .pc-menu-category > li > ul > li > a:focus {
  text-decoration: none;
  color: #444;
  background-color: #f5f5f5;  
}

.pc-menu .pc-menu-category .dropdown-header > a{
  color: #428bca;
  font-size: 16px;
  font-weight:bold;
}
</style>

<div class="navbar navbar-inverse pc-menu">
	<div class="navbar-inner mob-nav">
			<!-- Product Category Menu 	-->
        	<ul class="nav pc-menu-categories">
				<!-- Product Category link on desktop, tab etc -->
             	<li class="dropdown">             
                	<a class="dropdown-toggle" data-toggle="dropdown"  href="#">
                		<span class="hidden-phone"><?php echo JText::_('COM_PAYCART_PRODUCTCATEGORY'); ?> <b class="caret"></b></span>
                		<span class="visible-phone"><i class="fa fa-bars"> </i></span>
                	</a>
                	
                	<ul class="dropdown-menu pc-menu-category row-fluid">
                  		<!-- get ctaegory links -->
                  		<li class="">
                        	<ul class="pc-column unstyled">
                  				<?php $counter = 0;?>
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
				     						<ul class="pc-column unstyled">
				     			 			<?php $counter = 0;?>
				     					<?php endif;?>
				     				<?php endif;?>
				     			<?php endforeach;?>
                			</ul>
                		</li>
                	</ul>
            	</li>            
            <li>
				<form name="pc-menu-search-form" action="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>" method="get">
					<input type="text" class="navbar-search input-large search-query" placeholder="<?php echo JText::_("COM_PAYCART_SEARCH")?>" name="query"/>
				</form>
			</li>	
            
           </ul>
           
           
			<div class="pull-right">
				<ul class="nav">
           	    	<?php if (!$loggin_user->get('id')) : ?>
	           		<li>
	            		<a href="<?php echo JRoute::_('index.php?option=com_users&view=login');?>" >
	            			<i class="fa fa-user"></i>
	            			<span class="hidden-phone"> 
	            				<?php echo JText::_('COM_PAYCART_LOGIN_AND_REGISTER'); ?>	            			
	            			</span>	            		
	            		</a>            	
	            	</li>
            <?php else :?>
            		<?php $display_name = $loggin_user->get('name');?>
               		<li class="dropdown ">
            	    	<a class="dropdown-toggle " data-toggle="dropdown"  href="#">
                			<span class=" visible-phone">
	                    		<i class="fa fa-user fa-stack-1x"></i>
								<i class="fa fa-check fa-stack-1x text-info"></i>
							</span>
					        <span class="hidden-phone"> 
                    			<i class="fa fa-user"></i>
                    			<?php echo ucfirst($display_name); ?>
                    			<b class="caret"></b> 
                    		</span> 
                		</a>
                
               			<ul class="dropdown-menu ">
                  			<!-- Users links -->                    
                    		<li><a href="javascript::void();"><?php echo JText::_('COM_PAYCART_MODULE_MENU_HI');?>! <?php echo ucfirst($display_name); ?></a></li>
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
                <span class="hidden-phone"> 
                <?php echo JText::_('COM_PAYCART_CART'); ?></span>
                <span class="badge badge-info pc-demo-cart-counter"></span>
                </a>
            </li>
        </ul>         
    </div>	
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
