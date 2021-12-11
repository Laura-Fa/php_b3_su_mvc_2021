<?php

namespace App\Controller;

use Exception;

/**
 * Exception lancee lors d'une erreur a
 * l'inscription d'un utilisateur
 */
class RegisterException extends Exception
{
  public function __construct(string $message)
  {
    $this->message = $message;
  }
}
