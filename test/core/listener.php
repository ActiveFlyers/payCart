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
    }
 
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    }
    
    /**
      * Risky test.
      * @param PHPUnit_Framework_Test $test
      * @param Exception $e
      * @param float $time
      * @since Method available since Release 4.0.0
      * 
      */
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {}
}