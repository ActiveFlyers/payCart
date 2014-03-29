<?php

// get schema of attribute table 
$tmpl = include 'tmpl.php';

$row 	=	Array();

$row[] 	= 	array_replace($tmpl, Array( 
									'product_id'=>1,
									'productattribute_id'=>2,
									'productattribute_value'=>1,
									));
	
$row[] 	= 	array_replace($tmpl, Array(
									'product_id'=>1,
									'productattribute_id'=>1,
									'productattribute_value'=>2,
									));
	
	$row[] 	= 	array_replace($tmpl, Array(
									'product_id'=>2,
									'productattribute_id'=>2,
									'productattribute_value'=>2,
									));
	
	$row[] 	= 	array_replace($tmpl, Array(
									'product_id'=>2,
									'productattribute_id'=>1,
									'productattribute_value'=>1,
									));
	
	$row[] 	= 	array_replace($tmpl, Array(
									'product_id'=>1,
									'productattribute_id'=>3,
									'productattribute_value'=>4,
									));
	
	$row[] 	= 	array_replace($tmpl, Array(
									'product_id'=>1,
									'productattribute_id'=>3,
									'productattribute_value'=>2,
									));
	
	$row[] 	= 	array_replace($tmpl, Array(
									'product_id'=>1,
									'productattribute_id'=>4,
									'productattribute_value'=>1,
									));
	
	$row[] 	= 	array_replace($tmpl, Array(
									'product_id'=>2,
									'productattribute_id'=>3,
									'productattribute_value'=>1
									));
	
	$row[] 	= 	array_replace($tmpl, Array(
									'product_id'=>2,
									'productattribute_id'=>4,
									'productattribute_value'=>2,
									));
	
	
 
return  Array('jos_paycart_productattribute_value' => $row);
