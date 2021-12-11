<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private int $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private string $username;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private string $password;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private string $email;

 

  public function getId(): int
  {
    return $this->id;
  }


  public function getUsername(): string
  {
    return $this->username;
  }

  public function setUsername(string $username): self
  {
    $this->username = $username;

    return $this;
  }

  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

 
}
