<?php
/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/


/** 
 * Test Base Events
 * @author mManishTrivedi
 */
class PaycartEventTest extends PayCartTestCaseDatabase
{
	/**
	 * Test set of operation invoke on after Paycart-Attribute save
	 * 
	 * @return  void
	 */
	public function test_onAttributeAfterSave()
	{
		// Dependency : Create Mock for PaycartHelperProductFilter 
		// Sqlite have diffrent column difinition so need to mock getColumnDefinition method 
		$mock = $this->getMock('PaycartHelperProductFilter', Array('getColumnDefinition'));
		$mock->expects($this->any())
			 ->method('getColumnDefinition')
			 ->will($this->returnCallback(array(__CLASS__, 'callback_onAttributeAfterSave')));

		// Set Mockconfig
	  	PayCartTestReflection::setValue('PaycartFactory', '_mocks', Array('paycarthelperproductfilter' => $mock));
	  	
	  	foreach ($this->stub_onAttributeAfterSave() as $value) {  		
	  		
	  		list($data, $columnDefinition) = $value;
	  		
	  		// Automatic trigger
	  		$paycartAttribute = PaycartAttribute::getInstance(0, $data)->save(); 
			$this->assertInstanceOf('PaycartAttribute', $paycartAttribute);
			
			// get table fields
			$columns = PaycartFactory::getTable('productfilter')->getFields();
			
			foreach ($columnDefinition as $column=>$definition ) {
				$this->assertArrayHasKey($column, $columns, " $column is missing in Product-Filter table");
			}
			
			// remove dependancy
			PaycartFactory::cleanStaticCache(true);
	  	}
	}
	
	public function callback_onAttributeAfterSave($type) 
	{
		switch ($type)
		{
			case 'date':
			case 'text':
			case 'textarea':
			case 'list':
			default:
				$definition = ' TEXT ';
		}
		
		return $definition;
	}
	
	
	protected function stub_onAttributeAfterSave()
	{
		$rawData 	= array_pop(include RBTEST_PATH_DATASET.'/attribute/attribute-2.php');
		
		list($data1, $data2, $data3, $data4) = $rawData;
		
		// column nd column difinition
		$columnDefinition1 = Array(Paycart::PRODUCT_FILTER_FIELD_PREFIX.'1' => 'VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci' );
		$columnDefinition2 = array_merge($columnDefinition1, Array(Paycart::PRODUCT_FILTER_FIELD_PREFIX.'2' => 'VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci' ));
		$columnDefinition3 = $columnDefinition2;
		$columnDefinition4 = array_merge($columnDefinition3, Array(Paycart::PRODUCT_FILTER_FIELD_PREFIX.'4' => 'VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci' ));	
		
		return Array( 
						Array( $data1, $columnDefinition1), 
						Array( $data2, $columnDefinition2), 
						Array( $data3, $columnDefinition3), 
						Array( $data4, $columnDefinition4)
					);
		
	}
	
	// Var for table content
	var $test_onProductAfterSave = Array('_data/dataset/attribute/attribute-2.php');
		
	/**
	 * Test set of operation invoke on after Paycart-Product save 
	 *
	 * @return  void
	 */
	public function test_onProductAfterSave()
	{
		//CASE-1 : Insert data into index and filter table
		
		// build raw-data
		// attribute data {Array(attribute_id=>Array(value='', 'order'=>''))} in request data {attributes already exist in db}
		$attributes = Array(
						1 => Array('value'=>'_MANISH_', 	'order'=>1),
						2 => Array('value'=>'option-23', 	'order'=>2),
						3 => Array('value'=>'option-C', 	'order'=>3),
						4 => Array('value'=>'_PAYCART_',	'order'=>4),
						5 => Array('value'=>'_RBSL_', 		'order'=>5),
						6 => Array('value'=>'_RIM_', 		'order'=>6),					
						);
						
		// Post data 
		$data = Array(
						'title' 		=> 'Product-1',
						'alias' 		=> 'Product-1',
						'sku' 			=> 'Product-1',
						'category_id' 	=> 1,
						'attributes'	=> $attributes
					 );
					 
		$product = PaycartProduct::getInstance(0,$data)->save();
		$this->assertInstanceOf('PaycartProduct', $product);
		
		// get au data
		list($productIndexRow, $productFilterRow) = $this->auData_onProductAfterSave();
		// set au-data
		$au_data = Array(	"jos_paycart_productindex" => Array ($productIndexRow[1]),
							"jos_paycart_productfilter" => Array ($productFilterRow[1])
						 );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);

		$this->compareTables(Array('jos_paycart_productindex','jos_paycart_productfilter'), $expectedDataSet);
		
		//CASE-2 : Update data into index and filter table
		$attributes = Array(
						1 => Array('value'=>'_RIM_', 		'order'=>1),
						2 => Array('value'=>'option-24', 	'order'=>2),
						3 => Array('value'=>'option-B', 	'order'=>3),
						4 => Array('value'=>'_RBSL_',		'order'=>4),
						5 => Array('value'=>'_PAYCART_', 	'order'=>5),
						6 => Array('value'=>'_MANI_', 		'order'=>6),					
						);
						
		// Post data 
		$product->bind(Array('attributes'	=> $attributes))->save();
		
		$this->assertInstanceOf('PaycartProduct', $product);
		
		// Set au data
		$au_data = Array(	"jos_paycart_productindex" => Array ($productIndexRow[2]),
							"jos_paycart_productfilter" => Array ($productFilterRow[2])
						 );
		
		$expectedDataSet = new PHPUnit_Extensions_Database_DataSet_Specs_Array($au_data);

		$this->compareTables(Array('jos_paycart_productindex','jos_paycart_productfilter'), $expectedDataSet);
		
		
		
	}
	
	protected function auData_onProductAfterSave()
	{
		// Index data
		$productIndexTmpl 	=	include RBTEST_PATH_DATASET.'/productindex/tmpl.php';
		
		$productIndexRow[1]	= array_replace($productIndexTmpl, Array('product_id'=>1, 'content' => '_MANISH_ option-C _RIM_'));
		$productIndexRow[2]	= array_replace($productIndexTmpl, Array('product_id'=>1, 'content' => '_RIM_ option-B _MANI_'));
		
		// filter data
		$productFilterTmpl 	=	include RBTEST_PATH_DATASET.'/productfilter/tmpl.php';
		
		$productFilterRow[1]	= array_replace($productFilterTmpl, Array(	'product_id'=>1, 
																			Paycart::PRODUCT_FILTER_FIELD_PREFIX.'1' => '_MANISH_',
																			Paycart::PRODUCT_FILTER_FIELD_PREFIX.'2' => 'option-23',
																			Paycart::PRODUCT_FILTER_FIELD_PREFIX.'4' => '_PAYCART_'
																			));
		$productFilterRow[2]	= array_replace($productFilterTmpl, Array(	'product_id'=>1, 
																			Paycart::PRODUCT_FILTER_FIELD_PREFIX.'1' => '_RIM_',
																			Paycart::PRODUCT_FILTER_FIELD_PREFIX.'2' => 'option-24',
																			Paycart::PRODUCT_FILTER_FIELD_PREFIX.'4' => '_RBSL_'
																			));
																			
		return Array($productIndexRow, $productFilterRow);
	}
}