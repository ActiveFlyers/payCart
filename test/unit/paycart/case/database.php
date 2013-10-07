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
	 * Sets up the fixture.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 */
	private $_stashedPayCartState = array(
				'paycartfactory' =>Array('_config' => null)
			);
			
			
	
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
			// Get Joomla and Paycart db schema
			$schema  = file_get_contents(RBTEST_BASE . '/_data/database/ddl.sql');
			$schema .= file_get_contents(RBTEST_BASE . '/_data/database/paycart.sql');
			$pdo->exec($schema);

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
		// it will clean existing data and reset schema
		$files		= Array('_data/dataset/paycart.php');
		
		$testCase 	= $this->getName();
		// get test case specific db-content  
		if (isset($this->{$testCase})) {
			$files = array_merge($files, $this->{$testCase});
		}
		
		
		foreach ($files as $file) {
			
			$dataSetFile  = RBTEST_BASE."/$file";

			if(!JFile::exists($dataSetFile)) {
				throw new RuntimeException("DataSet File is not exist:: {$dataSetFile}");
			}
			
			// Array of all dataset
			$dataSet[] = new PHPUnit_Extensions_Database_DataSet_Specs_Array(include $dataSetFile);
		}

		// return Composit db
		return new PayCart_Database_DataSet_CompositeDataSet($dataSet);
	}
	
	
	/**
	 * 
	 * Compare Table
	 * @param $actualTable
	 * @param $excludeColumns
	 */
	protected function compareTable($actualTable, $expectedDataSet,  $excludeColumns = Array())
	{
		// get Current dataset 
		$actualDataSet	= $this->getConnection()->createDataSet(Array($actualTable));
		
		//Exclude columns
		if(!empty($excludeColumns)) {
         	$expectedDataSet	=	new PHPUnit_Extensions_Database_DataSet_DataSetFilter($expectedDataSet);
         	$actualDataSet  	=	new PHPUnit_Extensions_Database_DataSet_DataSetFilter($actualDataSet);
	        
         	$expectedDataSet->setExcludeColumnsForTable($actualTable, $excludeColumns);
	        $actualDataSet->setExcludeColumnsForTable($actualTable, $excludeColumns);
         }

         //Comapre Table   
        $this->assertDataSetsEqual($expectedDataSet, $actualDataSet);
	//  $this->assertTablesEqual($expectedDataSet->getTable($actualTable), $actualDataSet->getTable("$actualTable"));
	}
	
	protected  function setUp() 
	{	
		$this->saveSystemState();		
		parent::setUp();
	}
	
	
	/**
	 * Returns the database operation executed in test setup.
	 *
	 * @return  PHPUnit_Extensions_Database_Operation_DatabaseOperation
	 *
	 * @since   12.1
	 */
	protected function getSetUpOperation()
	{
		// Required given the use of InnoDB contraints.
		// IMP:: second argument specific for SQLite 
		return new PHPUnit_Extensions_Database_Operation_Composite(
			array(
				PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL(),
				new PHPUnit_Extensions_Database_Operation_DeleteSqliteSequence,
				PHPUnit_Extensions_Database_Operation_Factory::INSERT()
			)
		);
	}
	
	
	/**
	 * Returns the database operation executed in test cleanup.
	 *
	 * @return  PHPUnit_Extensions_Database_Operation_DatabaseOperation
	 *
	 * @since   12.1
	 */
	protected function getTearDownOperation()
	{
		// Required given the use of InnoDB contraints.
		return new PHPUnit_Extensions_Database_Operation_Composite(
			array(
				PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL(),
				new PHPUnit_Extensions_Database_Operation_DeleteSqliteSequence
			)
		);
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
			foreach ($properties as $prop=>$value) {
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
		$image = PaycartFactory::getHelper('image');
	}
}