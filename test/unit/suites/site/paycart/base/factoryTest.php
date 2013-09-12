<?php

/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/

/**
 * 
 * Test PaycartFactory 
 * @author mManishTrivedi
 *
 */
class PaycartFactoryTest extends PayCartTestCase
{
	/**
	 * (non-PHPdoc)
	 * @see test/unit/PayCartTestCase::setUp()
	 */
	protected function setUp() 
	{
		// load stub class
		require_once __DIR__.'/stub/factory.php';
		parent::setUp();
		;
	}
	
	/**
	 * Test the PaycartFactory::getInstance method.
	 *
	 * @return  void
	 */
	public function testGetInstance()
	{
		// Use try-cactch block if you are testing exception 
		// otherwise the code after the exception is thrown will not be executed.
		try {
        	//(test here prefix.type.name )
        	// Exception rise
			$this->assertInstanceOf(
					'PaycartControllerProduct',
					PaycartFactory::getInstance('product','controller'),
					'Line: ' . __LINE__
					);
        	
        } catch (RuntimeException $e) {
        	//IMP ::  Check assertion otherwise might be catch PHPUnit's exception. 
        	$this->assertSame('RB Factory::getInstance = Class paycartcontrollerproduct not found',trim($e->getMessage()));
        }			
		// Test FactoryStub instance
		$instance = PaycartFactory::getInstance('stub','factory');
		$this->assertInstanceOf(
				'PaycartFactorystub',
				 $instance,
				'Line: ' . __LINE__
				);
		// get cached instance
		$cachedInstance = PaycartFactory::getInstance('stub','factory');
		// Test :: Cached instance with previous created
		$this->assertEquals($instance, $cachedInstance);
		// Create new New instance 
		$newInstance = PaycartFactory::getInstance('stub','factory','paycart', true);
		// assign new value
		$newInstance->attribute_5 = 4;
		// Test :: Comapre cached and new created instance
		$this->assertNotEquals($instance, $newInstance);
		
	}
	
	/**
	 * Test the PaycartFactory::getConfig method.
	 *
	 * @return  void
	 */
	public function testGetConfig()
	{
		// Temporarily override the config cache in JFactory.
		$temp = JFactory::$config;

		// create Jregistry Mock object
		$mockRegistry = $this->getMock('JRegistry');
		$mockRegistry->expects($this->any())
					 ->method('loadArray')
					 ->will($this->returnValue(true));
		//assign to joomla config
		JFactory::$config = $mockRegistry;
		
		// Create a stub for the PaycartModelConfig class.
		$mockModel 	= $this->getMock('PaycartModelConfig', Array('loadRecords'));
		$dummyData1 = new stdClass;
		$dummyData2	= new stdClass;
		$dummyData1->key 	= '_rbsl_testing_key1';
		$dummyData1->value 	= '_rbsl_testing_value1';
		$dummyData2->key 	= '_rbsl_testing_key2';
		$dummyData2->value 	= '_rbsl_testing_value2';
		
		$mockModel->expects($this->any())
				  ->method('loadRecords')
				  ->will($this->returnValue(Array(1=>$dummyData1, 2=>$dummyData2)));
		
		// get Paycart config
		$paycartConfig 	= PaycartFactory::getConfig( null, 'PHP', '', $mockModel);
		// Test config should be instance of JRegistry
		$this->assertInstanceOf(
			'JRegistry',
			$paycartConfig,	
			'Line: ' . __LINE__
		);

		// PCTODO:: Add few more stuff with database
		// revert joomla config
		JFactory::$config = $temp;
	}
}
