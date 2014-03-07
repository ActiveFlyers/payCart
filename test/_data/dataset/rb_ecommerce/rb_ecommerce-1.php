<?php


$date 	= Rb_Date::getInstance()->toSql();

// get schema of Product table 
$tmpl_invoice 		= array_merge(Array('invoice_id'=>0), include 'tmpl_invoice.php');
$rows_invoice 		=	Array();

$rows_invoice[]	=  array_replace($tmpl_invoice, 
						Array(  'invoice_id' 	=> 6,	'object_id' 		=> 6, 	'object_type' 	=> 'paycartcart', 
								'buyer_id' 		=> 490,	'master_invoice_id' => 0,	'currency'		=> 'USD', 
								'time_price'	=> '{"time":["000000000000"],"price":["50.00000"]}',
								'sequence'		=> 1,'serial'	=>	'Inv-01-01', 'title'	=>	'Invoice-1', 
								'recurrence_count'  => 1, 'subtotal'=>  '50.00000', 'total'	=>	'50.00000',
								'created_date'		=> 	$date,						// 	Today is Created date
								'modified_date'		=>	$date, 						//	Today is Modified Date
								'issue_date'		=>	$date,						// Today is Issue date
								'paid_date'			=>	null, 'refund_date'=> null,
								'processor_type'	=>'', 'processor_config'=>'{}', 'processor_data'=>'{}'
							));

//$tmpl_transaction 	=	array_merge(Array('transaction_id'=>0), include 'tmpl_transaction.php');
//$rows_transaction 	=	Array();
//$rows_transaction[]	=  	array_replace($tmpl_transaction, 
//								Array(  'transaction_id' => 9, 'buyer_id'  => 0, 'invoice_id' => 0
//									));
								
// get all tables
$rb_ecommerce = include 'rb_ecommerce.php';

$rb_ecommerce['jos_rb_ecommerce_invoice'] = $rows_invoice;


return $rb_ecommerce;


