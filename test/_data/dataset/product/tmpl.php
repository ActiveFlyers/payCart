<?php

$date = Rb_Date::getInstance()->toSql();

return  Array(
//				'product_id'	=>	0, 
				'title'			=> 	null,	
				'alias'			=>	null,
				'published'		=>	1,
				'type'			=>	Paycart::PRODUCT_TYPE_PHYSICAL,
				'amount'		=> 	0,
				'quantity'		=>	0,
				'sku'			=>  null,	
				'variation_of'	=>	0,  	
				'category_id'	=>	0,
				'params'		=>	'{}',
				'cover_media'	=>	null, 	
				'teaser'		=>	null,
				'publish_up'	=>	$date,
				'publish_down'	=>	null,	 	
				'created_date'	=>	$date,	
				'modified_date'	=>	$date, 	
				'created_by'	=>	0,
				'ordering'		=>	0,
				'featured'		=>	0,	
				'description'	=>	null, 	
				'hits'			=>	0,
				'meta_data'		=>	'{}' 		//"{'title':'','descriprion':'','keyword':''}"
			);
				