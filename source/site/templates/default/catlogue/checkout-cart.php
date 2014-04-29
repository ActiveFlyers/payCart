<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}?>

<script>
	
</script>

<?php
$products = array();

// item1
$product = new stdClass();
$product->title = 'Vivamus auctor mi consequat arcu fermentum fringilla id adipiscing sapien';
$product->thumbnail = 'http://lorempixel.com/64/50/nature/1' ;
$product->price = 9;
$product->currency = '$';
$product->tax = 0;
$product->quantity= 1;
$product->total = 12345.67;
$product->specifications = array(
			'size' => 'XXL (small)',
			'color' => 'Yellow',
		);

$products[] = clone($product);

// item 2
$product = new stdClass();
$product->title = 'fermentum fringilla id adipiscing sapien';
$product->thumbnail = 'http://lorempixel.com/64/80/nature/2' ;
$product->price = 99.99;
$product->currency = '$';
$product->tax = 23.45;
$product->quantity= 2;
$product->total = 123;
$product->specifications = array(
		'size' => 'S (small)',
		'color' => 'Blue',
);

$products[] = clone($product);

// item 3
$product = new stdClass();
$product->title = 'sapien';
$product->thumbnail = 'http://lorempixel.com/64/64/nature/3' ;
$product->price = 99.99;
$product->currency = '$';
$product->tax = 2;
$product->quantity= 39;
$product->total = 12.2;
$product->specifications = array(
		'size' => 'S (small)',
		'color' => 'Blue',
);

$products[] = clone($product);

// item 4
$product = new stdClass();
$product->title = 'Vivamus auctor mi consequat arcu fermentum fringilla id adipiscing sapien';
$product->thumbnail = 'http://lorempixel.com/64/90/nature/4' ;
$product->price = 99.99;
$product->currency = '$';
$product->tax = 3.5;
$product->quantity= 125;
$product->total = 99.99;
$product->specifications = array(
		'size' => 'S (small)',
		'color' => 'Blue',
);

$products[] = clone($product);
?>
<form>
<div class='pc-checkout-wrapper clearfix'>
	 <div class="pc-checkout row-fluid">


	 	<!-- top-buttons -->
	 	<div class="row-fluid ">
	        <span class="pull-left"> CART <span class="muted">(4 items)</span> </span>
	        <span class="pull-right text-error"><strong> TOTAL = $ 1500 </strong></span>
	 	</div>
	 	
	 	<hr />
	 	
 		<div class="clearfix">
			<div class="pull-left ">	 			
		        <button class="btn"><i class="fa fa-angle-left"></i> Back</button>
		    </div>
		    <div class="pull-right">	 			
		       <button class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Checkout</button>
		    </div>
		</div>

		<!-- products listing -->
		<hr />
		<?php foreach($products as $item):?>
		<div class="row-fluid pc-item">
			<div class="pull-left pc-grid-4">
				<h4><img class="thumbnail" src="<?php echo $item->thumbnail;?>" /></h4>
			</div>
			<div class="pull-right pc-grid-8">
				 <h4 class="text-info"><?php echo $item->title; ?></h4>
				 <p class="pc-item-attribute">
				 	<span class="muted">size:</span> &nbsp;<span><?php echo $item->specifications['size']?></span><br />
				 	<span class="muted">color:</span> &nbsp;<span><?php echo $item->specifications['color']?></span><br />
				 	<span class="muted">unit price:</span> &nbsp;<span><?php echo $item->currency,' ', $item->price; ?></span><br />

				 	<?php if($item->tax>0):?>
				 	<span class="muted">+ Tax </span><span><?php echo $item->tax;?> %</span><br />
				 <?php endif;?> 
				 </p>
				 
				<div class="clearfix">
					<div class="pull-left pc-grid-4">
					 	 <label class="muted">Quantity</label>
				 		 <input class="pc-grid-6" type="number" value="<?php echo $item->quantity; ?>" />
					</div>
					
					<div class="pull-right text-right">
					 	 <label class="muted">Price</label>
				 		 <h3><?php echo $item->currency,$item->total; ?></h3>
					</div>
			 	</div> 
			 	
			 	<div class="clearfix">
				 	 <a class="pull-right muted" href="#"><i class="fa fa-trash-o fa-lg">&nbsp;</i></a>
			 	</div>
			 	
			</div>
			
		</div>
		<hr />
		<?php endforeach;?>
		
		<p class="lead text-right">
			<span>Estimated Total = </span><span class="text-error"><strong>$1234567.89</strong></span>
		</p>
		<p class="small text-right"><a href="#" >Delivery charges may apply</a></p>
 		
 		<!-- footer buttons -->
 		<div class="clearfix">
			<div class="pull-left ">	 			
		        <button class="btn"><i class="fa fa-angle-left"></i> Back</button>
		    </div>
		    <div class="pull-right">	 			
		       <button class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Checkout</button>
		    </div>
		</div>

	 </div>	 
</div>
</form>
<?php

