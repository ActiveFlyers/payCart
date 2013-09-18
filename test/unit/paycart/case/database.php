<?php
/**
 * @package    Paycart.Test
 * @author   mManishTrivedi
 */

/**
 * Abstract test case class for database testing.
 *
 * @package  Paycart.Test
 * @author   mManishTrivedi
 */
abstract class PayCartTestCaseDatabase extends TestCaseDatabase
{
	/**
	 * @var    JDatabaseDriver  The saved database driver to be restored after these tests.
	 * @since  12.1
	 */
	private static $_stash;
	
	/**
	 * This method is called before the first test of this test class is run.
	 *
	 * @return  void
	 *
	 * @see TestCaseDatabase::setUpBeforeClass
	 */
	public static function setUpBeforeClass()
	{
		// We always want the default database test case to use an SQLite memory database.
		$options = array(
			'driver' => 'sqlite',
			'database' => ':memory:',
			'prefix' => 'jos_'
		);

		try
		{
			// Attempt to instantiate the driver.
			self::$driver = JDatabaseDriver::getInstance($options);

			// Create a new PDO instance for an SQLite memory database and load the test schema into it.
			$pdo = new PDO('sqlite::memory:'); 
			// PCTODO:: Get Joomla and Paycart db schema
			$pdo->exec(file_get_contents(RBTEST_BASE . '/_data/database/ddl.sql'));

			// Set the PDO instance to the driver using reflection whizbangery.
			TestReflection::setValue(self::$driver, 'connection', $pdo);
		}
		catch (RuntimeException $e)
		{
			self::$driver = null;
		}

		// If for some reason an exception object was returned set our database object to null.
		if (self::$driver instanceof Exception)
		{
			self::$driver = null;
		}

		// Setup the factory pointer for the driver and stash the old one.
		self::$_stash = JFactory::$database;
		JFactory::$database = self::$driver;		
	}
	
	/**
	 * This method is called after the last test of this test class is run.
	 *
	 * @return  void
	 */
	public static function tearDownAfterClass()
	{
		JFactory::$database = self::$_stash;
		self::$driver = null;
	}
	
	/**
	 * Gets the data set to be loaded into the database during setup.
	 *
	 * @return  PHPUnit_Extensions_Database_DataSet_XmlDataSet
	 */
	protected function getDataSet()
	{
		$class = new ReflectionObject($this);
		$path = dirname($class->getFileName());
		$path = $path . '/stubs/'.get_class($this).'/'.$this->getName().'.xml';
		// If dataset file exist then load it 		
		if(file_exists($path)) {
			return $this->createXMLDataSet($path);
		}

		return new PHPUnit_Extensions_Database_DataSet_NullDataSet;
	}
}
