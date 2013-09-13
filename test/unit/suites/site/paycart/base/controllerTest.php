<?php

/** 
 * Test Base Controller
 * @author mManishTrivedi
 */
class PaycartControllerTest extends PayCartTestCase
{
	protected function setUp()
	{
		if (!defined('JPATH_COMPONENT')) {
			define('JPATH_COMPONENT', JPATH_BASE . '/components/com_paycart');
		}
		parent::setUp();
	}
	
	/**
	 * Tests the PayCartController constructor.
	 *
	 * @return  void
	 */
	public function testConstructor() 
	{
		// We are using new instance not singleton instance 
		$paycartController = new PaycartController();
		// public attribute
		$paycart = $paycartController->_component;
		// Should be instance of Rb_extension class	
		//$this->assertTrue(is_a($paycart, 'Rb_extension'));
		$this->assertInstanceOf('Rb_extension', $paycart);
		//Check all rb_extension attributes
		$this->assertAttributeEquals('paycart',		'prefix_css', 	$paycart, 'Checks the prefix_css variable was created properly.');
		$this->assertAttributeEquals('Paycart', 	'prefix_class', $paycart, 'Check the prefix_class variable was created properly.');
		$this->assertAttributeEquals('COM_PAYCART', 'prefix_text', 	$paycart, 'Check the prefix_text variable was created properly.');
		$this->assertAttributeEquals('PAYCART', 	'name_caps', 	$paycart, 'Check the _view_list variable was created properly.');
		$this->assertAttributeEquals('paycart', 	'name_small', 	$paycart, 'Checks the name_caps variable was created properly.');
		$this->assertAttributeEquals('com_paycart', 'name_com', 	$paycart, 'Check the name_small variable was created properly.');
		$this->assertAttributeEquals('pa', 			'name_css', 	$paycart, 'Check the name_css variable was created properly.');
		
		$reflection  = new ReflectionClass('PaycartController');
		//test parent class
		$this->assertEquals('Rb_Controller', $reflection->getParentClass()->getname(), 'Check Parent Class');
  		//test class property
  		$this->assertTrue($reflection->hasProperty('_component'));
  		//Check number of attributes and attributes default values in class
  		$this->assertEquals(Array('_component'), PaycartTestReflection::getClassAttribute('PaycartController'));
	}
}
