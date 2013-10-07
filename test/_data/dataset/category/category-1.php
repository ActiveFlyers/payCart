<?php

// get schema of attribute table 
$tmpl = include 'tmpl.php';

$row 	=	Array();


$row[] 	= 	array_replace($tmpl, Array(	'title'=>'Cat-1', 'alias'=>'cat-1', 
										'ordering'=>1,'created_by'=>489 ));
									
										
$row[] 	= 	array_replace($tmpl, Array(	'title'=>'Cat-2', 'alias'=>'cat-2', 
										'ordering'=>2, 'created_by'=>489));
 
return  Array('jos_paycart_category' => $row);