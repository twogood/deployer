<?php

namespace models;

class LastErrorException extends \ErrorException
{
  private $error;

  public function __construct($message = null, $error = null)
  {
    if (!$error)
      $error = error_get_last();

    if (!$message)
      $message = $error['message'];

    $this->error = $error;
    parent::__construct($message, 0, $error['type'], $error['file'], $error['line']);
  }

}

