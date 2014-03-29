<?php

// get schema of attribute table 
list($tmpl, $tmpl_lang) = include 'tmpl.php';

$row 	 =	Array();
$rowLang =	Array();

$row[] 	= 	array_replace($tmpl, Array("productattribute_id" => 1 , 
										"type"=> 'select', 
										"css_class"=>'class-attribute-1',
									   	"searchable"=>1,
									   	"ordering"=>1, 
									   	"status" => 'published'));

$rowLang[] = array_replace($tmpl_lang, Array("title" => 'Attribute-1',
											 "productattribute_lang_id" => 1,
											 "productattribute_id" => 1,
											 "lang_code"=> 'en-GB'));

$row[] 	= 	array_replace($tmpl, Array("productattribute_id" => 2 , 
									   "type"=> 'radio',
                                       "css_class"=>'class-attribute-2',
									   "searchable"=>1,
									   "ordering"=>2, 
									   "status" => 'published'));

$rowLang[] = array_replace($tmpl_lang, Array("title" => 'Attribute-2', 
                                             "productattribute_lang_id" => 2,
                                             "productattribute_id" => 2,
                                             "lang_code"=> 'fr-FR'));

$row[] 	= 	array_replace($tmpl, Array("productattribute_id" => 3 , 
                                       "type"=> 'checkbox', 
                                       "css_class"=>'class-attribute-3',
                                       "searchable"=>1,
                                       "ordering"=>3,
                                       "status" => 'published'));

$rowLang[] = array_replace($tmpl_lang, Array("title" => 'Attribute-A', 
                                             "productattribute_lang_id" => 3,
                                             "productattribute_id" => 3,
                                             "lang_code"=> 'en-GB'));

$row[] 	= 	array_replace($tmpl, Array("productattribute_id" => 4 , 
                                       "type"=> 'color', 
                                       "css_class"=>'class-attribute-4',
                                       "searchable"=>1,
                                       "ordering"=>4, 
                                       "status" => 'invisible'));

$rowLang[] = array_replace($tmpl_lang, Array("title" => 'Attribute-B', 
											 "productattribute_lang_id" => 4,
											 "productattribute_id" => 4,
											 "lang_code"=> 'en-GB'));
 
return  Array('jos_paycart_productattribute' => $row,
			  'jos_paycart_productattribute_lang'=> $rowLang);
