<?php

// get schema of Product table
$product  = include 'tmpl_product.php';
$category = include 'tmpl_category.php';

$catlogue1 = new stdClass();

$catlogue1->category = array();

$catlogue1->category[] 	=  array_replace($category, Array(  "category_id" =>1, "title" => 'Men', 									"ordering" => 1, "cover_media" => 'http://lorempixel.com/200/200/cats/1'));
$catlogue1->category[]	=  array_replace($category, Array(  "category_id" =>2, "title" => 'Lorem ipsum dolor sit amet', 			"ordering" => 2, "cover_media" => 'http://lorempixel.com/100/100/cats/2'));
$catlogue1->category[]	=  array_replace($category, Array(  "category_id" =>3, "title" => 'Donec in magna quis ante tempor sodales', "ordering" => 3, "cover_media" => 'http://lorempixel.com/50/50/cats/3'));
$catlogue1->category[]	=  array_replace($category, Array(  "category_id" =>4, "title" => 'Clothes', "ordering" => 5,					 "cover_media" => 'http://lorempixel.com/200/200/cats/4'));
$catlogue1->category[]	=  array_replace($category, Array(  "category_id" =>5, "title" => 'Mobiles', "ordering" => 4, "cover_media" => 'http://lorempixel.com/200/200/cats/5'));
$catlogue1->category[]	=  array_replace($category, Array(  "category_id" =>6, "title" => 'Morbi aliquet libero', "ordering" => 6, "cover_media" => 'http://lorempixel.com/200/200/cats/6'));
$catlogue1->category[]	=  array_replace($category, Array(  "category_id" =>7, "title" => 'mauris dapibus', "ordering" => 7, "cover_media" => 'http://lorempixel.com/400/400/cats/7'));
$catlogue1->category[]	=  array_replace($category, Array(  "category_id" =>8, "title" => 'feugiat semper', "ordering" => 8, "cover_media" => 'http://lorempixel.com/400/300/cats/8'));
$catlogue1->category[]  =  array_replace($category, Array(  "category_id" =>9, "title" => 'gravida', "ordering" => 9, "cover_media" => 'http://lorempixel.com/170/170/cats/9'));

$catlogue1->product = array();

$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>1, "title" => 'loremnulla', "ordering" => 1, "cover_media" => 'http://lorempixel.com/200/100/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>2, "title" => 'lorem ultrices tempus', "ordering" => 2, "cover_media" => 'http://lorempixel.com/200/150/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>3, "title" => 'loremimpsumtincidunt', "ordering" => 3, "cover_media" => 'http://lorempixel.com/300/200/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>4, "title" => 'fringilla nulla malesuada', "ordering" => 4, "cover_media" => 'http://lorempixel.com/400/200/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>5, "title" => 'Donec a nulla nec', "ordering" => 5, "cover_media" => 'http://lorempixel.com/100/200/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>6, "title" => 'consequat arcu', "ordering" => 6, "cover_media" => 'http://lorempixel.com/100/50/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>7, "title" => 'arcu fermentum fringilla id adipiscing', "ordering" => 7, "cover_media" => 'http://lorempixel.com/400/200/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>8, "title" => 'non dui ut augue', "ordering" => 8, "cover_media" => 'http://lorempixel.com/400/200/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>9, "title" => 'malesuada, id lacinia metus faucibus', "ordering" => 9, "cover_media" => 'http://lorempixel.com/500/200/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>10, "title" => 'Men', "ordering" => 10, "cover_media" => 'http://lorempixel.com/320/180/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>11, "title" => 'Men', "ordering" => 11, "cover_media" => 'http://lorempixel.com/600/150/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>12, "title" => 'Curabitur non', "ordering" => 12, "cover_media" => 'http://lorempixel.com/390/120/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>13, "title" => 'sollicitudin', "ordering" => 13, "cover_media" => 'http://lorempixel.com/100/240/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>14, "title" => 'Vivamus auctor mi consequat arcu fermentum fringilla id adipiscing sapien.', "ordering" => 14, "cover_media" => 'http://lorempixel.com/400/200/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>15, "title" => 'amet quam cursus', "ordering" => 15, "cover_media" => 'http://lorempixel.com/400/200/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>16, "title" => 'consectetur a in dui', "ordering" => 16, "cover_media" => 'http://lorempixel.com/200/400/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>17, "title" => 'fringilla consectetur', "ordering" => 17, "cover_media" => 'http://lorempixel.com/200/400/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>18, "title" => 'euismod', "ordering" => 18, "cover_media" => 'http://lorempixel.com/400/200/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>19, "title" => 'In vitae justo aliquam', "ordering" => 19, "cover_media" => 'http://lorempixel.com/600/150/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>20, "title" => 'Sed auctor diam et scelerisque congue', "ordering" => 20, "cover_media" => 'http://lorempixel.com/500/220/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>21, "title" => 'Vivamus vel quam ut enim posuere placerat vel eu lacus', "ordering" => 21, "cover_media" => 'http://lorempixel.com/470/215/animals'));
$catlogue1->product[] =  array_replace($product, Array(  "product_id" =>22, "title" => 'In auctor risus ut viverra venenatis', "ordering" => 22, "cover_media" => 'http://lorempixel.com/373/187/animals'));

return $catlogue1;