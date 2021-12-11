<?php

namespace App\Controller;

use Exception;

/**
 * Exception lancee lors d'une erreur de connexion
 */
class LoginException extends Exception
{
  public function __construct(string $message)
  {
    $this->message = $message;
  }
}
