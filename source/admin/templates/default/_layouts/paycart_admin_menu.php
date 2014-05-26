<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		team@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );
?>
<style>
#pc-admin-menu .accordion-group{
	border: 0px;
}

#pc-admin-menu{
	border: 1px solid #cccccc;
	border-radius: 10px;
	padding: 10px 5px 10px 10px;
}

#pc-admin-menu a {
	background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 0px;
    color: #999999;
    display: block;
    font-size: 12px;
    font-weight: bold;
    line-height: 20px;
    padding: 15px;
    text-decoration: none;    
}

#pc-admin-menu .accordion-group ul a{	
	background: none repeat scroll 0 0 #FFFFFF;
    border: 0px;
    color: #999999;
    display: block;
    font-size: 11px;
    font-weight: bold;
    line-height: 20px;
    padding: 8px 10px 8px 40px;
    text-decoration: none;
}

#pc-admin-menu .accordion-group > a,
#pc-admin-menu .accordion-group ul.in{
	border-bottom: 1px dashed #cccccc;
}
#pc-admin-menu .accordion-group:last-child > a,
#pc-admin-menu .accordion-group:last-child ul.in{
	border-bottom: 0;
	border-top: 1px dashed #cccccc;
}

#pc-admin-menu a:hover,
#pc-admin-menu a.active,
#pc-admin-menu .accordion-group ul a:hover,
#pc-admin-menu .accordion-group ul a.active{
	background-color: transparent;	
	color: #555;
}

#pc-admin-menu a:focus{
	outline: none;
}

#pc-admin-menu .caret{
	float: right;
	width: 0;
	height: 0;
	display: inline-block;
	vertical-align: top;
	border-bottom: 4px solid;	
	border-right: 4px solid transparent;
	border-left: 4px solid transparent;
	border-top: 0px;
	content: "";
    margin-top: 8px;
	margin-left: 2px;
}

#pc-admin-menu .collapsed .caret {
	border-bottom: 0px;
	border-top: 4px solid;	
	border-right: 4px solid transparent;
	border-left: 4px solid transparent;
}

#pc-admin-menu .accordion-group a > i{
	margin-right: 5px;
}
</style>

<script>
(function($){
	$(document).on('click.collapse.data-api', '#pc-admin-menu .accordion-toggle', function(event) {
	    var $this = $(this),
	        parent = $this.data('parent'),
	        $parent = parent && $(parent);
	
	    if ($parent) {
	        $parent.find('[data-toggle=collapse][data-parent=' + parent + ']').not($this).addClass('collapsed');	        
	    }
	});
})(paycart.jQuery);
</script>

<ul id="pc-admin-menu" class="accordion nav nav-department nav-tabs nav-stacked">
<?php 			
	foreach ($displayData->menus as $id => $menu) :	
		$childmenuHtml = '';
		$isCurrentActive = false;
		if(isset($menu['children'])):
			ob_start();
			?>			
				 
					<?php 
					foreach($menu['children'] as $childmenu) :
						$active = false;
						if($childmenu['url'] == $displayData->currentUrl):
							$active = true;
							$isCurrentActive = true;
						endif;						
						?>
						<li>
							<a <?php echo $active ? 'class="active"' : '';?> href="<?php echo $childmenu['url'];?>"><?php echo $childmenu['title'];?></a>
						</li>
						<?php 
					endforeach;
					?>
				
			<?php 
			$content = ob_get_contents();
			ob_end_clean();
			$childmenuHtml .= $content;
		else:
			if($displayData->currentUrl == $menu['url']):
				$isCurrentActive = true;
			endif;
		endif;					
		?>
    
		<li class="accordion-group">			
				<?php if(isset($menu['children'])) :?>
					<a href="<?php echo $menu['url'];?>" data-toggle='collapse' data-target='#pc-admin-menu-<?php echo $id;?>', data-parent='#pc-admin-menu' class="accordion-toggle <?php echo $isCurrentActive ? 'active' : 'collapsed';?>">
						<i class="<?php echo isset($menu['class']) && !empty($menu['class']) ? $menu['class'] : '';?>"></i>						
						<span class="caret pull-right"></span>
						<?php echo $menu['title'];?>  
					</a>
				<?php else:?>
					<a href="<?php echo $menu['url'];?>" data-toggle='collapse' data-target='#pc-admin-menu-<?php echo $id;?>', data-parent='#pc-admin-menu' class="accordion-toggle <?php echo $isCurrentActive ? 'active' : 'collapsed';?>">
						<i class="<?php echo isset($menu['class']) && !empty($menu['class']) ? $menu['class'] : '';?>"></i>
						<?php echo $menu['title'];?>  
					</a>
					
				<?php endif;?>		
			
			
			<?php if(!empty($childmenuHtml)) :?>
				<ul id="pc-admin-menu-<?php echo $id;?>" class="nav nav-list collapse <?php echo $isCurrentActive ? 'in' : '';?>">
					<?php echo $childmenuHtml;?>
				</ul>
			<?php endif;?>
		</li>
	<?php endforeach;?>	
</ul>
<?php 