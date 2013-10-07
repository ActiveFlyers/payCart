<?php
/**
 * @package    Paycart.Test
 *
 */

/**
 * Abstract test case class for unit testing.
 *
 * @package  Paycart.Test
 * @author	 mManishTrivedi
 */
abstract class PayCartTestCase extends TestCase
{
	/**
	 * Sets up the fixture.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 */
	private $_stashedPayCartState = array(
				'paycartfactory' =>Array('_config' => null)
			);
			
	protected function setUp()
	{ 
		$this->saveSystemState();
		parent::setUp();
	}

	/**
	 * Tears down the fixture.
	 * This method is called after a test is executed.
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
		$this->restoreSystemState();
		parent::tearDown();
	}
	
	/**
	 * 
	 * Before test case save system state. includeing Joomla, Rbframwork and Paycart 
	 * like store cached value
	 */
	protected function saveSystemState() 
	{
		// first save joomla state
		$this->saveFactoryState();
		
		// RB_framwork : Clean static Cache
		Rb_Factory::cleanStaticCache(true);
		
		foreach ($this->_stashedPayCartState as $entity => $properties) {
			foreach ($properties as $prop => $value) {
				$this->_stashedPayCartState[$entity][$prop] = PayCartTestReflection::getValue($entity, $prop);
			}
		}
	}
	
	protected function restoreSystemState() 
	{
		// restore save joomla state
		$this->restoreFactoryState();
		
		// RB_framwork : Clean static Cache
		Rb_Factory::cleanStaticCache(true);
		
		foreach ($this->_stashedPayCartState as $entity => $properties) {
			foreach ($properties as $prop=>$value) {
				PayCartTestReflection::setValue($entity, $prop, $this->_stashedPayCartState[$entity][$prop]);
			}
		}
	}
	
	
}