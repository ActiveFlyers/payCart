<?php
/**
 * @package     Paycart.Site
 * @subpackage  mod_paycart_cart
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;	
$categories		= PaycartAPI::getCategories();
$loggin_user 	= PaycartFactory::getUser();

$link = isset($displayData->return_link) ? $displayData->return_link : 'index.php';
$return_link	= 	base64_encode($link);

?>

<style>
.text-white{ color: #fff; }

/*-----------Off Canvas -----------------------*/
.pc-mob .navbar-inner {
	padding-right: 0;padding-left: 0;
}
.navbar-text { 
	margin-left:0 
}
.navbar-text{
	margin-top: 10px;
	margin-bottom: 10px;
}

.navbar-text > li {
	display: inline-block;
	padding: 0px 18px;
	font-size: 18px;
}
.dropdown-menu {} 
.navbar-text > li {}
.mega-dropdown {  
	position: static !important;  
	width:100%;
}
.mega-dropdown-menu {
	padding: 20px;
	box-shadow: none;
	-webkit-box-shadow: none;
}
.mega-dropdown-menu > li > ul {
	padding: 0;
	margin: 0;
}
.mega-dropdown-menu > li > ul > li {
	list-style: none;
}
.mega-dropdown-menu > ul > li > a {
	display: block;
	padding: 3px 20px;
	clear: both;
	font-weight: normal;
	line-height: 1.428571429;
	color: rgb(153, 153, 153);
	white-space: normal;
}
.mega-dropdown-menu > li ul > li > a:hover,
.mega-dropdown-menu > li ul > li > a:focus { 
	text-decoration: none;  
	color: #444;  
	background-color: #f5f5f5;
}
.mega-dropdown-menu .dropdown-header > a{  
	color: #428bca;  font-size: 18px;  font-weight:bold;
}
.mega-dropdown-menu .form-group {    
	margin-bottom: 3px;
}
.navbar-toggle { 
	display: block;  
	margin-bottom: 0;
}
.dropdown-header{	
	padding:0;
}
.sidebar-offcanvas .close{
	margin-top: 10px;
	margin-right: 20px;
}
.navbar-search {
	margin-top: 0;
}
</style>

<div class="pc-mob">
	<div class="navbar navbar-inverse" role="navigation">
	      <div class="navbar-inner mob-nav text-center">
	          <ul class="navbar-text unstyled ">
	          	<li>
	          		<div data-toggle="offcanvas" data-target="#allcategories">
		        		<span class="text-white fa fa-bars"></span>
		      		</div>  
		      	</li>
		      	
	          	<li>
					<span class="text-white fa fa-search" data-toggle="offcanvas" data-target="#searchbar"></span>
				</li>
				
				<?php if (!$loggin_user->get('id')) : ?>
			           <li class="dropdown ">
				        	<a class="dropdown-toggle " data-toggle="dropdown"  href="#">
				            		
					            	<i class="fa fa-user text-white "></i>
									 <b class="fa fa-caret-down text-white"></b>
							</a>
						
				            <ul class="dropdown-menu ">
			            		<li>
	                       			<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=account&task=login&action=track');?>">
							     		<i class="fa fa-map-marker"> </i> <?php echo JText::_('MOD_PAYCART_MENU_TRACK_ORDER');?>
							     	</a>
	                       		</li>
	                       		<li class="divider"></li>
	                       		<li>
	                       			<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=account&task=login&action=login');?>">
							     		<i class="fa fa-user"> </i> <?php echo JText::_('COM_PAYCART_LOGIN_AND_REGISTER');?>
							     	</a>
	                       		</li>
	                		</ul>
			            </li>
				<?php else : $display_name = $loggin_user->get('name'); ?>	
						<li class="dropdown ">
				        	<a class="dropdown-toggle " data-toggle="dropdown"  href="#">
				            		
					            	<i class="fa fa-user text-white "></i>
									 <b class="fa fa-caret-down text-white"></b>
							</a>
						
				            <ul class="dropdown-menu ">
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
							<i class="fa fa-shopping-cart text-white"></i>                
			            	<span class="badge badge-info pc-demo-cart-counter"></span>
			        	</a>
					</li>
	          </ul>
	      </div>
	</div>
	
	<div class="hide" id="pc-mob-offcanvas">
		<div class="sidebar-offcanvas" role="navigation" id="allcategories">
			<div class="sidebar-offcanvas-inner">
				<span class="close" data-toggle="offcanvas" data-target="#allcategories"><i class="fa fa-times fa-2x"></i></span>
				<div class="mega-dropdown-menu row">
					
					<div class="dropdown-header">
						<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>">
							<?php echo JText::_('COM_PAYCART_All') .' '.JText::_('COM_PAYCART_PRODUCTCATEGORY'); ?>
			            </a>
		            </div>
					<ul class="unstyled">
				    	<?php $counter = 0;?>
						<?php foreach( $categories as $cat): ?>
					     	<?php if (!$cat->level == 0):?>	
					     		<li class="<?php if ($cat->level == 1) { echo 'dropdown-header'; };?>">
					     			<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display&productcategory_id='.$cat->productcategory_id);?>" class="<?php echo 'pc-level'.$cat->level;?>">
					     				<?php echo $cat->title;?>
					     			</a>
					     		</li>
					     		<?php if ($counter == 10){ ?>
					     			</ul>
					     			<ul class="unstyled"><?php 				     			
					     			 $counter = 1;}?> 
					     		<?php $counter++;?>
					     	<?php endif;?>
					     	<hr>
					     <?php endforeach;?>
					</ul>
				</div>
			</div>
		</div>
	
		<div class="sidebar-offcanvas" id="searchbar" role="navigation">
			<div class="sidebar-offcanvas-inner">
				<span class="close" data-toggle="offcanvas" data-target="#searchbar"><i class="fa fa-times fa-2x"></i></span>
				<div class="mega-dropdown-menu">				
					<form class="form-search" name="pc-menu-search-form" action="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>" method="get">
						<div class="input-append">
							<input type="text" class="navbar-search input-large search-query" placeholder="<?php echo JText::_("COM_PAYCART_SEARCH")?>" name="query"/>
						  	<button type="submit" class="btn">Search</button>
						</div>
					</form>
				</div>
			</div>
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
	$(document).ready(function () {
		$('.sidebar-offcanvas').hide();
	  	$('[data-toggle="offcanvas"]').click(function () {
		  var currentId = $(this).attr('data-target');
		  $('.sidebar-offcanvas').hide();
		  	setTimeout( function(){ 
		  		$(currentId).show();
			  }
			 , 100 );		  
		  
	    $('.row-offcanvas').toggleClass('active');
	  });
	});
})(jQuery); 
(function($){
	$('.pc-menu-popover')
    	.popover()  	//pophover
        .mouseleave(	//on mouse leave hide it
        function() {
        	$('.pc-menu-popover').popover('hide')
		});
        var pc_menu = {};   			 
        pc_menu.update = {
    	onSuccess : function(response_data)    					{
        $('.pc-demo-cart-counter').html();        					
		// 	take action
        if ( response_data['products_count'] > 0 ) {
    		$('.pc-demo-cart-counter').html(response_data['products_count']);
        }
    },
	onError : function(response_data) {
    	console.log ( {" response contain error :  " : response_data } );
    },    					
    do : function(event) {
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
    		$('body').addClass('row-offcanvas row-offcanvas-left');
    		$('#pc-mob-offcanvas').removeClass('hide');    		
  		  
    	});
})(paycart.jQuery);        
</script>
