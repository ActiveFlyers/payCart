<?php
 
$date = Rb_Date::getInstance()->toSql();

 return Array( 				
				'buyer_id' 			=> 0,
				'address_id'		=> 0,
				'modifiers'			=> '{}',
				'subtotal' 			=> 0.00,
				'total'				=> 0.00,
			    'currency' 			=> null,
				'status'			=> Paycart::CART_STATUS_NONE,
				'created_date'  	=> $date,	
				'modified_date' 	=> $date,
				'checkout_date' 	=> '0000-00-00 00:00:00',
				'paid_date'     	=> '0000-00-00 00:00:00',
				'complete_date'  	=> '0000-00-00 00:00:00',
				'cancellation_date' => '0000-00-00 00:00:00',
				'refund_date'	 	=> '0000-00-00 00:00:00',
				'params'			=> '{}'
			 ); 