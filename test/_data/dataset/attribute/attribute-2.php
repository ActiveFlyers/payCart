<?php

/**
 * File have searchable and filterable Attribute
 */

// get schema of attribute table 
$tmpl 	= include 'tmpl.php';

$row 	=	Array();

$row[]	= 	array_replace($tmpl, Array(
									"title"=>'Attribute-1', "type"=> 'text', "default"=>'deafault-attribut-1',"class"=>'class-attribute-1',
									"filterable"=>1, "searchable"=>1,
									"params"=>'{"attribute_config":{"type":"text","size":"25","maxlength":"16","readonly":"0","disabled":"0"}}',
									"xml"=> "<field name  = 'value' label = 'Attribute-1' class= 'class-attribute-1' default= 'deafault-attribut-1' 
													type='text'  size='25'  maxlength='16'  readonly='0'  disabled='0' >"
									));
									
$row[]	= 	array_replace($tmpl, Array(
									"title"=>'Attribute-2', "type"=> 'list', "default"=>'option-22',"class"=>'class-list',
									"filterable"=>1,  "searchable"=>0,
									"params"=>'{"attribute_config":{"type":"list","options":"option-21\r\noption-22\r\noption-23\r\noption-24\r\noption-25","multiple":"false","size":"3","readonly":"0","onchange":""}}',
									"xml"=> "<field name  = 'value' label = 'Attribute-2' class= 'class-list' default= 'option-22' type='list'  multiple='false'  size='3'  readonly='0'  onchange='' >
												<option value='option-21'>option-21</option><option value='option-22'>option-22</option>
												<option value='option-23'>option-23</option><option value='option-24'>option-24</option>
											</field>" 
									));
									
$row[] 	= 	array_replace($tmpl, Array(
									"title"=>'Attribute-A', "type"=> 'list', "default"=>'option-D',
									"filterable"=>0, "searchable"=>1,
									"params"=>'{"attribute_config":{"type":"list","options":"option-A\r\noption-B\r\noption-C\r\noption-D\r\noption-E\r\noption-F","multiple":"true","size":"4","readonly":"0","onchange":""}}',
									"xml"=> "<field name  = 'value'  label = 'Attribute-A' default= 'option-D' type='list'  multiple='true'  size='4'  readonly='0'  onchange='' >
												<option value='option-A'>option-A</option><option value='option-B'>option-B</option>
												<option value='option-C'>option-C</option><option value='option-D'>option-D</option>
											 </field>" 
									));
																	
$row[] 	= 	array_replace($tmpl, Array(
									"title"=>'Attribute-B', "type"=> 'text', "default"=>'trial',
									"filterable"=>1, "searchable"=>0,
									"params"=>'{"attribute_config":{"type":"text","size":"15","maxlength":"6","readonly":"0","disabled":"0"}}',
									"xml"=> "<field  name  = 'value' label = 'Attribute-B'  default= 'trial' type='text'  size='15'  maxlength='6'  readonly='0'  disabled='0' >
											 </field>"
									));
									
$row[] 	= 	array_replace($tmpl, Array(
									"title"=>'Attribute-a', "type"=> 'text', "default"=>'trial-a',
									"filterable"=>0, "searchable"=>0,
									"params"=>'{"attribute_config":{"type":"text","size":"15","maxlength":"6","readonly":"0","disabled":"0"}}',
									"xml"=> "<field  name  = 'value' label = 'Attribute-a'  default= 'trial-a' type='text'  size='15'  maxlength='6'  readonly='0'  disabled='0' >
											 </field>"
									));

$row[] 	= 	array_replace($tmpl, Array(
									"title"=>'Attribute-3', "type"=> 'text', "default"=>'trial-3',
									"filterable"=>0, "searchable"=>1,
									"params"=>'{"attribute_config":{"type":"text","size":"15","maxlength":"6","readonly":"0","disabled":"0"}}',
									"xml"=> "<field  name  = 'value' label = 'Attribute-a'  default= 'trial-3' type='text'  size='15'  maxlength='6'  readonly='0'  disabled='0' >
											 </field>"
									));

return  Array('jos_paycart_attribute' => $row);