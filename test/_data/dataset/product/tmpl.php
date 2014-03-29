<?php

$date = Rb_Date::getInstance()->toSql();

return  Array(
           Array( 
           //sequence should be same as actual the table fields
           		'type'			=>	Paycart::PRODUCT_TYPE_PHYSICAL,
				'productcategory_id'	=> 	0,	
				'status'		=>	'',
           		'variation_of'	=>	0,
           		'sku'			=>  '',
				'price'			=> 	0.00,
				'quantity'		=>	0,
           		'featured'		=>	0,
           		'cover_media'	=>	null,
           		'stockout_limit'=>  0,
					
				'weight'		=> 0.00,
				'weight_unit'	=> '',	
           		'height'	 	=> 0.00,
				'length'		=> 0.00,
				'depth'		 	=> 0.00,
				
				'dimension_unit'=> '',
           		'config'		=> '',
				'created_date'	=>	$date,	
				'modified_date'	=>	$date, 	
				'ordering'		=>	0
			), 
			Array(
				'product_id'			=>	0,
				'lang_code'				=>	PaycartFactory::getLanguageTag(),
				'title'					=> 	'',
				'alias'					=>	'',
				'description'			=>	'',
				'teaser'				=>	'',	
				'metadata_title'		=>	'',
				'metadata_keywords'		=>	'', 
				'metadata_description'	=>	'' 
			)
		);
