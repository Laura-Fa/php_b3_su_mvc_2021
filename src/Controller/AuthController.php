<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;
use Doctrine\ORM\EntityManager;


class AuthController extends AbstractController
{
  /**
   * Registration of a new user
   *
   * @param EntityManager $em
   * @param string $username
   * @param string $password
   * @param string $email
   * @return void
   */
  #[Route(path: "/register", name: "register", httpMethod: "POST")]
  public function register(EntityManager $em, string $username, string $password, string $email)
  {   
      /*
      * Verification des entrees utilisateur
      */
      if(empty($username) || empty($password) || empty($email) ){
        throw new RegisterException("Les champs ne peuvent pas être vides.");
      }

      // Username doit etre unique
      $dql = "SELECT COUNT(u.id) as compte FROM App\Entity\User u WHERE u.username= :name";
      $query = $em->createQuery($dql);
      $query->setParameter('name', htmlspecialchars($username));
      $nb = $query->getResult();

      if($nb[0]['compte'] != 0){
        throw new RegisterException("Ce username est déjà utilisé.");
      }

      if(strlen($password) < 8){
        throw new RegisterException("La longueur du mot de passe doit être de 8 caractères au minimum.");
      }

      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        throw new RegisterException("L'adresse mail n'est pas valide.");
      }
  
      /*
      * Ajout dans la BDD
      */
      $user = new User();
      // Chiffrement du mot de passe
      $passwordHash = password_hash($password, PASSWORD_ARGON2ID);
 
      $user->setUsername(htmlspecialchars($username))
          ->setPassword($passwordHash)
          ->setEmail(htmlspecialchars($email));

      // On demande au gestionnaire d'entités de persister l'objet
      // Attention, à ce moment-là l'objet n'est pas encore enregistré en BDD
      $em->persist($user);
      $em->flush();
  }


}