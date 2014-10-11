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
</style>

<div class="navbar navbar-inverse">
	
  <div class="navbar-inner mob-nav">
  
<!-- Product Category Menu 	-->
        <ul class="nav">
            
            <li class="visible-phone" data-toggle="collapse" data-target=".nav-collapse">
                <a href="#"><i class="fa fa-bars"></i></a>
            </li>
			
			<!-- Product Category link on desktop, tab etc -->
             <li class="dropdown hidden-phone">
             
                <a class="dropdown-toggle" data-toggle="dropdown"  href="#">
                	<?php echo JText::_('COM_PAYCART_PRODUCTCATEGORY'); ?> 
                	<b class="caret"></b></a>
                <ul class="dropdown-menu ">
                  <!-- get ctaegory links -->
                    <?php foreach( $categories as $cat): ?>
                        <li>
                        	<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display&productcategory_id='.$cat->productcategory_id);?>">
                        		<?php echo $cat->title; ?>
                        	</a>
                       </li>
                    <?php endforeach;?>
                </ul>
            </li>
            
           </ul>
           
           <ul class="nav pull-right">
           
            <li class="hidden-phone pc-menu-popover"
            	data-content="Coming Soon!!"  
            	data-placement="bottom"
            	>
            	<a href="javascript:void()">
	            	<i class="fa fa-rocket"></i>
	            	<span class="hidden-phone">
	            	<?php echo JText::_('COM_PAYCART_WHATS_NEW'); ?></span>
            	</a>
           </li>
            
           
           <li class="pc-menu-popover"
            	data-content="Coming Soon!!" 
            	data-placement="bottom"
            	">
            	<a href="javascript:void()">
	            	<i class="fa fa-gift"></i>
	            	<span class="hidden-phone"> <?php echo JText::_('COM_PAYCART_OFFERS'); ?> </span>
            	</a>
           </li>
           
           
           <li class="pc-menu-popover"
            	data-content="Coming Soon!!" 
            	data-placement="bottom"
            	">
            	<a href="javascript:void()">
	            	<i class="fa fa-map-marker"></i>
	            	<span class="hidden-phone"> <?php echo JText::_('COM_PAYCART_TRACK_ORDER'); ?></span>
            	</a>
           </li>
           
            <li><a href='<?php echo JRoute::_('index.php?option=com_paycart&view=cart&task=display');?>'>
                <i class="fa fa-shopping-cart"></i>
                <span class="hidden-phone"> 
                <?php echo JText::_('COM_PAYCART_CART'); ?></span>
                <span class="badge badge-info pc-demo-cart-counter"></span>
                </a>
            </li>
           
           <?php if (!$loggin_user->get('id')) : ?>
	           	<li>
	            	<a href="<?php echo JRoute::_('index.php?option=com_users&view=login');?>" >
	            		<i class="fa fa-user"></i>
	            		<span class="hidden-phone"> 
	            			<?php echo JText::_('COM_PAYCART_LOGIN_AND_REGISTER'); ?> 
	            		</span>
	            	</a>
	            </li>
            <?php else :
            		$display_name = $loggin_user->get('name');
            ?>
            
             <li class="dropdown ">
                <a class="dropdown-toggle " data-toggle="dropdown"  href="#">
                	<span class=" visible-phone">
	                    <i class="fa fa-user fa-stack-1x"></i>
						<i class="fa fa-check fa-stack-1x text-info"></i>
					</span>
					
                    <span class="hidden-phone"> 
                    	<i class="fa fa-user"></i>
                    	<?php echo ucfirst($display_name); ?>
                    </span> 
                </a>
                
                <ul class="dropdown-menu ">
                  <!-- Users links -->
                    
                    	<li class="pc-menu-popover"
			            	data-content="Coming Soon!!" 
			            	data-placement="bottom"
			            	data-trigger="hover"
			            	">
			            	<a href="#">
				            	<span class=""> <?php echo JText::_('COM_PAYCART_DASHBOARD'); ?> </span>
			            	</a>
			           </li>
           
                    	<li>
                        	<a href='<?php echo JRoute::_('index.php?option=com_users&view=profile&layout=edit');?>'> 
                        		<?php echo 'Profile-Edit'; ?>
                        	</a>
                       </li>
                       
                       <li>
                       		<a href="#" onclick='pc_menu_logout(); return false;' >
                       			<?php echo JText::_('JLOGOUT'); ?>
                       		</a>
                       </li>
                       
                       <li class ="hide">
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
                       </li>
                </ul>
                
            </li>
            
            
            	
            <?php endif;?>
            
        </ul>
         
    
    	<!-- Product Category link on mobile etc -->    
        <div class="nav-collapse collapse visible-phone">
        	<ul class="nav">
              <!-- get ctaegory links -->
                <?php  foreach( $categories as $cat): ?>
                    <li>
                    	<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display&productcategory_id='.$cat->productcategory_id);?>">
                    		<?php echo $cat->title; ?>
                    	</a>
                    </li>
                    
                <?php endforeach;?>
            </ul>   
       </div>
        
        
  </div>
  
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
    			})

        	})(paycart.jQuery);
        
        </script>

