<?php


//tmpl with default values
return  Array(
//  'invoice_id'  => '',
			  'object_id' 			=>  0 ,
			  'object_type'			=> 'paycartcart', 		// Must be paycartcart
			  'buyer_id' 			=>  0,
			  'master_invoice_id' 	=>  0,
			  'currency' 			=>  'NULL',
			  'sequence' 			=>  0,
			  'serial' 				=> '' ,
			  'status' 				=> PaycartHelperInvoice::STATUS_INVOICE_DUE,			// default is 401			
			  'title' 				=> 'NULL',
			  'expiration_type'		=> PaycartHelperInvoice::INVOICE_EXPIRATION_TYPE_FIXED,	// default fixed type stuff
			  'time_price' 			=> '',
			  'recurrence_count'	=> 0,
			  'subtotal' 			=> 0.00000,
			  'total' 				=> 0.00000,  
			  'notes' 				=> '',
			  'params' 				=> '',
			  'created_date' 		=> '',
			  'modified_date'		=> '',
			  'paid_date'	 		=> 'NULL',
			  'refund_date'  		=> 'NULL',
			  'due_date'  			=> '0000-00-00 00:00:00',			// infinite time
			  'issue_date' 			=> 'NULL',
			  'processor_type' 		=> 'NULL',							// dynamic add Processor information
			  'processor_config' 	=> '',
			  'processor_data' 		=> '{}'
) ;
				