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

<?php



$product = new stdClass();
$product->title = 'Vivamus auctor mi consequat arcu fermentum fringilla id adipiscing sapien';
$product->screenshots = array(
			'http://lorempixel.com/64/64/nature/1' => 'http://lorempixel.com/400/400/nature/1/', 
			'http://lorempixel.com/64/128/nature/2' => 'http://lorempixel.com/400/400/nature/2/',
			'http://lorempixel.com/64/32/nature/3' => 'http://lorempixel.com/400/400/nature/3/',
			'http://lorempixel.com/64/90/nature/4' => 'http://lorempixel.com/400/400/nature/4/',
			'http://lorempixel.com/64/50/nature/4' => 'http://lorempixel.com/400/400/nature/5/',
		);

$product->price = '99.99';
$product->currency = '$';
$product->details = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
$product->specifications = array();

	$a1 = new stdClass();
	$a1->is_header = true;
	$a1->label = 'General details';
	$a1->value = null;
	$product->specifications[] = $a1;
	
	$a1 = new stdClass();
	$a1->is_header = false;
	$a1->label = 'Ideal For';
	$a1->value = 'mens';
	$product->specifications[] = $a1;
	
	$a1 = new stdClass();
	$a1->is_header = false;
	$a1->label = 'Occassion';
	$a1->value = 'Casual';
	$product->specifications[] = $a1;

?>

<?php 
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/js/owl.carousel.min.js');
Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/css/owl.carousel.css');
?>

<script>
paycart.queue.push(' $("#pc-screenshots-carousel").owlCarousel({ lazyLoad : true, singleItem:true, autoHeight : true, pagination:true }); ');
</script>

<div class='pc-product-fullview-wrapper row-fluid clearfix'>

	<h1 class="visible-phone"><?php echo $product->title; ?></h1>
	 
	 <div class="row-fluid">
		 <!-- left  laytout  -->
		 <div class="span6">
		 	<div id="pc-screenshots-carousel" class="owl-carousel pc-screenshots">
			 	<?php $counter = 0; ?>
			    <?php foreach($product->screenshots as $thumb => $full):?>
				    <div>
				    	<img class="lazyOwl" data-src="<?php echo $full;?>" />
				    </div>
				    <?php $counter++; ?>
				<?php endforeach;?>
	 		</div>
	 		
	 		
		 </div>
	
		 <!-- Right layout -->
		 <div class="span6">
				<h1 class="hidden-phone"><?php echo $product->title; ?></h1> 		
		 		<h2>Price : <span class="currency">$</span> <span class="amount">210.00</span></h2>			
				<p class="muted">
					Original price : <del><span class="currency">$</span> <span class="amount">399.00</span></del>
					<br />(inclusive of taxes)
				</p>					
					
		 		<!-- show extra attribute or information here via app -->
		 		<div>
		 		    <form>
					    <fieldset>
					    	<label class="muted">Select Size:</label>
					    	<select>
								<option>S</option>
								<option>M</option>
								<option>L</option>
								<option>XL</option>
								<option>XXL</option>
							</select>
					    	
					    	<span class="help-block">Example block-level help text here.</span>
					    </fieldset>
				    </form>
    			</div>
		 		
		 		<!-- buy now -->
		 		<div class="row-fluid">
		 			<button class="button btn-block btn-large btn-primary ">Buy Now</button>
		 			<button class="button btn-block btn-large">Add to Cart</button>
		 		</div>
		 		
		 		
		 </div>
	 </div>
	 
	 <!-- full layout -->
	 <div class="row-fluid">
	 
	  <div class="span12">
	 	<!-- accordion1 -->
	 	<div class="accordion" id="accordion-id">
	 		<div class="accordion-group">
		 		<div class="accordion-heading">
		 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-id" data-target=".accordion-body-id">
		 				<h2>Details</h2>
		 			</a>		
		 		</div>
		 		<!-- use class "in" for keeping it open -->
		 		 <div class="accordion-body collapse in accordion-body-id">
		 		 	<div class="accordion-inner">
		 		 		<h3>content of a page when looking at its layout.</h3>
		 		 	 	<p>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
		 		 	</div>
		 		 </div>
	 		 </div>
	 	</div>
	 	
	 	<div class="accordion" id="accordion-id2">
	 		<div class="accordion-group">
		 		<div class="accordion-heading">
		 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-id2" data-target=".accordion-body-id2">
		 				<h2>Specification</h2>
		 			</a>		
		 		</div>
		 		
		 		 <div class="accordion-body collapse accordion-body-id2">
		 		 	<div class="accordion-inner">
		 		 		<h3>content of a page when looking at its layout.</h3>
		 		 	 	<p>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
		 		 	</div>
		 		 </div>
	 		 </div>
	 	</div>
	 	
	 	<div class="accordion" id="accordion-id3">
	 		<div class="accordion-group">
		 		<div class="accordion-heading">
		 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-id3" data-target=".accordion-body-id3">
		 				<h2>Shipping</h2>
		 			</a>		
		 		</div>
		 		
		 		 <div class="accordion-body collapse accordion-body-id3">
		 		 	<div class="accordion-inner">
		 		 		<h3>content of a page when looking at its layout.</h3>
		 		 	 	<p>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
		 		 	</div>
		 		 </div>
	 		 </div>
	 	</div>
	 	
	 	</div>
	 </div>
</div>
<?php

