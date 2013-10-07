<?php

// get schema of attribute table 
$tmpl = include 'tmpl.php';

$row 	=	Array();

$row[] 	= 	array_replace($tmpl, Array( 
//									'attributevalue_id'=>10,
									'product_id'=>1,
									'attribute_id'=>2,
									'value'=>'option-21',
									'order'=>2));
	
$row[] 	= 	array_replace($tmpl, Array(
//									'attributevalue_id'=>9,
									'product_id'=>1,
									'attribute_id'=>1,
									'value'=>'text-attribute',
									'order'=>1));
	
	$row[] 	= 	array_replace($tmpl, Array(
//									'attributevalue_id'=>14,
									'product_id'=>2,
									'attribute_id'=>2,
									'value'=>'option-22',
									'order'=>6));
	
	$row[] 	= 	array_replace($tmpl, Array(
//									'attributevalue_id'=>13,
									'product_id'=>2,
									'attribute_id'=>1,
									'value'=>'attr-1',
									'order'=>5));
	
	$row[] 	= 	array_replace($tmpl, Array(
//									'attributevalue_id'=>11,
									'product_id'=>1,
									'attribute_id'=>3,
									'value'=>'option-A,option-B',
									'order'=>3));
	
	$row[] 	= 	array_replace($tmpl, Array(
//									'attributevalue_id'=>12,
									'product_id'=>1,
									'attribute_id'=>4,
									'value'=>'Attr',
									'order'=>4));
	
	$row[] 	= 	array_replace($tmpl, Array(
//									'attributevalue_id'=>15,
									'product_id'=>2,
									'attribute_id'=>3,
									'value'=>'option-B,option-C,option-D',
									'order'=>7));
	
	$row[] 	= 	array_replace($tmpl, Array(
//									'attributevalue_id'=>16,
									'product_id'=>2,
									'attribute_id'=>4,
									'value'=>'attr-b',
									'order'=>8));
	
	
 
return  Array('jos_paycart_attributevalue' => $row);