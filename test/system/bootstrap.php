<?php

require_once 'defines.php';
require_once RBTEST_SYSTEM_SERVER_CONFIG;
require_once 'RbWebdriverTestCase.php';


class SeleniumClientAutoLoader {

	// Array of page class files
	private $pageClassFiles = array();

	public function __construct()
	{
		spl_autoload_register(array($this, 'seleniumClientLoader'));
		$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(RBTEST_SYSTEM_PAGES),
				RecursiveIteratorIterator::SELF_FIRST
		);
		foreach ($iterator as $file)
		{
			if ($file->isFile())
			{
				$this->pageClassFiles[substr($file->getFileName(), 0, (strlen($file->getFileName()) - 4))] = (string) $file;
			}
		}
	}

	private function seleniumClientLoader($className)
	{
		$fileName = str_replace("\\", "/", $className) . '.php';
		if (file_exists(RBTEST_SYSTEM_BASE.$fileName))
		{
			include_once RBTEST_SYSTEM_BASE.$fileName;
		}
		elseif (isset($this->pageClassFiles[$className]) && file_exists($this->pageClassFiles[$className]))
		{
			include_once $this->pageClassFiles[$className];
		}
	}
}

$autoloader = new SeleniumClientAutoLoader();