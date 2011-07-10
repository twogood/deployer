<?php

class LastErrorExceptionTest extends PHPUnit_Framework_TestCase
{
  public function testConstructWithoutParameters()
  {
    $errorMessage = 'this is not a real error';
    $errorType = E_USER_NOTICE;
    @trigger_error($errorMessage, $errorType);
    $exception = new \models\LastErrorException();
    $this->assertEquals($errorMessage, $exception->getMessage());
    $this->assertEquals(0, $exception->getCode());
    $this->assertEquals($errorType, $exception->getSeverity(), 'error type aka severity must be '.E_USER_NOTICE);
    $this->assertEquals(__FILE__, $exception->getFile());
  }

  public function testConstructorWithError()
  {
    $error = array(
        'type' => 2,
        'message' => 'some error message',
        'file' => 'stuff.php',
        'line' => 42
      );
    $exception = new \models\LastErrorException(null, $error);
    $this->assertEquals($error['type'], $exception->getSeverity());
    $this->assertEquals($error['message'], $exception->getMessage());
    $this->assertEquals($error['file'], $exception->getFile());
    $this->assertEquals($error['line'], $exception->getLine());
  }  

  public function testConstructorWithMessage()
  {
    $error = array(
        'type' => 2,
        'message' => 'some error message',
        'file' => 'stuff.php',
        'line' => 42
      );
    $anotherMessage = 'another error message';
    $exception = new \models\LastErrorException($anotherMessage, $error);
    $this->assertEquals($error['type'], $exception->getSeverity());
    $this->assertEquals($anotherMessage, $exception->getMessage());
    $this->assertEquals($error['file'], $exception->getFile());
    $this->assertEquals($error['line'], $exception->getLine());
  }  

}

