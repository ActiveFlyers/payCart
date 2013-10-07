<?php

// get schema of Product table 
$tmpl = array_merge(Array('product_id'=>0), include 'tmpl.php');

$rows 	=	Array();

		
$rows[]	=  array_replace($tmpl, Array(  "product_id" =>1, "title" => 'Product-1', "alias" => 'product-1',
										"sku" => 'product-1', "ordering" => 1));

$rows[]	=  array_replace($tmpl, Array(  "product_id" =>2, "title" => 'product-2', "alias" => 'product-2',
										"sku" => 'product-2',"category_id" => 1, "ordering" => 2 ));

$rows[]	=  array_replace($tmpl, Array( 	"product_id" =>3,  "title" => 'product-3', "alias" => 'product-3',
										"sku" => 'product-3', "ordering" => 3 ));

$rows[]	=  array_replace($tmpl, Array( 	"product_id" =>4,  "title" => 'product-4', "alias" => 'product-4',
										"sku" => 'product-4', "ordering" => 4 ));

$rows[]	=  array_replace($tmpl, Array( 	"product_id" =>5,  "title" => 'product-5',"alias" => 'product-5',
										"sku" => 'product-5',"ordering" => 5, 'variation_of'=>1 ));

$rows[]	=  array_replace($tmpl, Array( "product_id" =>6,  "title" => 'product-6',"alias" => 'product-6',"sku" => 'product-6',"ordering" => 6 ));

$rows[]	=  array_replace($tmpl, Array( "product_id" =>7,  "title" => 'product-7',"alias" => 'product-7',"sku" => 'product-7',"ordering" => 7, 'variation_of'=>3 ));

$rows[]	=  array_replace($tmpl, Array( "product_id" =>8, "title" => 'product-8',"alias" => 'product-8',"sku" => 'product-8',"ordering" => 8, 'variation_of'=>4 ));

return  Array('jos_paycart_product' => $rows );
	