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
	protected $sqlDataSet = true;
	
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
		$class = new ReflectionObject($this);
		$path = dirname($class->getFileName());
		$path = $path . '/stubs/'.get_class($this).'/'.$this->getName().'.xml';
		// If dataset file exist then load it 		
		if(file_exists($path)) {
			if($this->sqlDataSet) {
				return $this->createMySQLXMLDataSet($path);
			}
			return $this->createXMLDataSet($path);
		}

		return new PHPUnit_Extensions_Database_DataSet_NullDataSet;
	}
	/**
	 * 
	 * Compare Table
	 * @param $actualTable
	 * @param $excludeColumns
	 */
	protected function compareTable($actualTable, $excludeColumns = Array())
	{
		// get Current dataset 
		$actualDataSet	= $this->getConnection()->createDataSet(Array($actualTable));
		
		// get stub path
		// @Assumption 
		$class = new ReflectionObject($this);
		$path = dirname($class->getFileName());
		$path = $path . '/stubs/'.get_class($this).'/au_'.$this->getName().'.xml';		
		//expected dataset
		// If dataset file exist then load it 		
		if(!file_exists($path)) {
			throw RuntimeException("##### Gold Table Not found :: $path ####");
		}
		// Get Expected Dataset
		if($this->sqlDataSet) {
			$expectedDataSet = $this->createMySQLXMLDataSet($path);
		}else{
			$expectedDataSet =  $this->createXMLDataSet($path);
		}
		
		//Exclude columns
		if(!empty($excludeColumns)) {
         	$expectedDataSet	=	new PHPUnit_Extensions_Database_DataSet_DataSetFilter($expectedDataSet);
         	$actualDataSet  	=	new PHPUnit_Extensions_Database_DataSet_DataSetFilter($actualDataSet);
	        
         	$expectedDataSet->setExcludeColumnsForTable($actualTable, $excludeColumns);
	        $actualDataSet->setExcludeColumnsForTable($actualTable, $excludeColumns);
         }

         //Comapre Table                              
		$this->assertDataSetsEqual($expectedDataSet, $actualDataSet);
	}
	
	protected  function setUp() 
	{
		// Clean static Cache
		Rb_Factory::cleanStaticCache(true);
		parent::setUp();
	}
	
}
