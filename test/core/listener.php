<?php
class RbTestListener implements PHPUnit_Framework_TestListener
{
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        printf("Error while running test '%s' \n", $test->getName());
    }
 
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        printf(" \n Test '%s' failed ", $test->getName());
    }
 
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        printf("\n Test '%s' is incomplete ", $test->getName());
    }
 
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        printf("\n Test '%s' has been skipped ", $test->getName());
    }
 
    public function startTest(PHPUnit_Framework_Test $test)
    {
        printf("\n%s", $test->getName());
    }
 
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
//        printf("Test '%s' ended.\n", $test->getName());
    }
 
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    	if($suite->getName()) {
        	printf("\n-------- STARTED :: %s --------", $suite->getName());
    	}
    }
 
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    	if($suite->getName()) {
        	printf("\n-------- ENDED :: %s -------- \n", $suite->getName());
    	}
    }
}