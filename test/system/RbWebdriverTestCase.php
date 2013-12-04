<?php

// core joomla webdriver file
require_once 'JoomlaWebdriverTestCase.php';

// our wrapper Class
class RbWebdriverTestCase extends JoomlaWebdriverTestCase
{

	
	private static $_stash_db ;
	private static $_stash_config ;
	private static $_pdo;
	
    /**
     * This method is called before the first test of this test class is run.
     *
     * @since Method available since Release 3.4.0
     */
    public static function setUpBeforeClass()
    {
    	// TODO:: need to clean-up
    	$cfg = new SeleniumConfig();
    	
    	################## CREATE NEW DATABASE ##################
    	// root access
    	$root		 	= "root"; 
		$root_password 	= "password"; 
		
		try {
			
	        self::$_pdo = new PDO("mysql:host=localhost", $root, $root_password);
	        
	        // create new testing DB
	        self::$_pdo->exec(	
	        			"CREATE DATABASE `{$cfg->db_name}`; ".
	                	//"CREATE USER '{$cfg->db_user}'@'localhost' IDENTIFIED BY '{$cfg->db_pass}';
	                	"GRANT ALL ON `{$cfg->db_name}`.* TO '{$cfg->db_user}'@'localhost'; ".
	                	"FLUSH PRIVILEGES;"
	        		) OR die("\n". print_r(self::$_pdo->errorInfo(), true));

	       
	       // set database
	       self::$_pdo->query("use {$cfg->db_name}");
	       
	      // Get Joomla db schema and load it
	      $schema  = file_get_contents(RBTEST_BASE . '/_data/database/database.mysql');
	      self::$_pdo->exec($schema);
	    } catch (PDOException $e) {
	        die("\n DB ERROR: ". $e->getMessage());
	    } catch (Exception $e) {
	        die("\n DB ERROR: ". $e->getMessage());
	    }
   	
	    ################## CREATE NEW Configuration ##################
	    
		// create custom config
		$config = (Array)  new JConfig();
		
		//stash the config stat.
		self::$_stash_config = $config;
		 
		// Setup the new config  
		$config['debug'] 			=  1;
		$config['error_reporting'] 	= 'maximum';
		
		$config['dbtype']			= $cfg->db_type;
		//$config['host'] 			= 'localhost';
		//$config['user'] 			= $cfg->db_user;
		//$config['password'] 		= $cfg->db_pass;
		$config['db']				= $cfg->db_name;
		$config['dbprefix'] 		= $cfg->db_prefix;
		
		self::changeConfigFile($config);

	}
	
	/**
	 * This method is called after the last test of this test class is run.
	 *
	 * @return  void
	 */
	public static function tearDownAfterClass()
	{
		$cfg = new SeleniumConfig();

		################## Revert testing stuff ##################
		// reverse prev config
		self::changeConfigFile(self::$_stash_config);
		// Drop test suite's db
		self::$_pdo->exec(	"DROP DATABASE `{$cfg->db_name}`; ");
		
		self::$_stash_config 	= null;
    }
    
    public function setUp() 
    {
    	// TODO :: write code for test specific data
    	parent::setUp();
    }
    
	public function tearDown() 
    {
    	// TODO :: write code for revert test specific data
    	parent::tearDown();
    }
    
    /**
     * 
     * It will update joomla configuration file
     * @param $args => key value pair
     */
    public static function changeConfigFile($args) 
    {		
		// formatter
		$formatter = new JRegistryFormatPHP();
		
		//load prev config
		$config = new JConfig();
		
		// update new config
		foreach($args as $k=>$v)
		{
			if(isset($config->$k)) {
				$config->$k = $v;
		        //echo " Updating [$k |:::| $v]";
		        continue;
			}
			//echo " ERROR **** Invalid config [$k |:::| $v]";
		}
		
		$params = array('class' => 'JConfig', 'closingtag' => false);
		$str = $formatter->objectToString($config, $params);
		// write new config
		file_put_contents(JPATH_BASE.'/configuration.php', $str);
    }
}
