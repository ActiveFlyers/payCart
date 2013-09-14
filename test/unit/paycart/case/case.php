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
	protected function setUp()
	{ 
		$this->saveFactoryState();

		JFactory::$application = $this->getMockApplication();
		JFactory::$config = $this->getMockConfig();

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
		$this->restoreFactoryState();
		parent::tearDown();
	}
}