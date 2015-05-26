<?php 

require_once('Dollar.php');

class MoneyTest extends PHPUnit_Framework_TestCase
{
  public function setUp(){}
  
  public function tearDown(){}

  public function testMultiplication() {
    $five = new Dollar(5);
    $five->times(2);
    $this->assertEquals(10, $five->amount);
  }
}
?>