<?php

namespace App\Controller;

use Exception;

class RegisterException extends Exception
{
  public function __construct(string $message)
  {
    $this->message = $message;
  }
}
