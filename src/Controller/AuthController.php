<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;
use Doctrine\ORM\EntityManager;


class AuthController extends AbstractController
{
  /**
   * Affichage de la page d'inscription
   *
   * @return void
   */
  #[Route(path: "/registerForm", name: "showRegisterUserForm", httpMethod: "GET")]
  public function showRegisterUserForm()
  {
    echo $this->twig->render('user/userRegistrationForm.html.twig');
  }

  /**
   * Affichage de la page de connexion
   *
   * @return void
   */
  #[Route(path: "/loginForm", name: "showLoginUserForm", httpMethod: "GET")]
  public function showLoginUserForm()
  {
    echo $this->twig->render('user/userLoginForm.html.twig');
  }

  /**
   * Enregistrement d'un nouvel utilisateur
   *
   * @param EntityManager $em
   * @param string $username
   * @param string $password
   * @param string $email
   * @return void
   */
  #[Route(path: "/register", name: "register", httpMethod: "POST")]
  public function register(EntityManager $em)
  {        
      // lecture informations utilisateur
      $username = $_POST["username"];
      $password = $_POST["password"];
      $email = $_POST["email"];

      // Verification des entrees utilisateur
      if(empty($username) || empty($password) || empty($email) ){
        throw new RegisterException("Les champs ne peuvent pas être vides.");
      }

      // Username doit etre unique
      $dql = "SELECT COUNT(u.id) as compte FROM App\Entity\User u WHERE u.username= :name";
      $query = $em->createQuery($dql);
      $query->setParameter('name', htmlspecialchars($username));
      $nb = $query->getResult();

      if($nb[0]['compte'] != 0){
        throw new RegisterException("Ce pseudo est déjà utilisé.");
      }

      if(strlen($password) < 8){
        throw new RegisterException("La longueur du mot de passe doit être de 8 caractères au minimum.");
      }

      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        throw new RegisterException("L'adresse mail n'est pas valide.");
      }
       
      // Ajout dans la BDD
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

      // redirection vers la page de connexion
      header('Location:loginForm');
  }


  /**
   * Connexion de l'utilisateur
   *
   * @param EntityManager $em
   * @return void
   */
  #[Route(path: "/login", name: "login", httpMethod: "POST")]
  public function login(EntityManager $em)
  {
    // lecture informations utilisateur
    $username = $_POST["username"];
    $password = $_POST["password"];

    $dql = "SELECT u.password FROM App\Entity\User u WHERE u.username= :name";
    $query = $em->createQuery($dql);
    $query->setParameter('name', htmlspecialchars($username));
    $passwordHash = $query->getResult()[0]['password'];
    
    // verification existance de l'utilisateur
    if($passwordHash == null){
      throw new LoginException("Nom d'utilisateur/Mot de passe incorrect.");
    }

    if(password_verify($password, $passwordHash)) {
      $_SESSION['username'] = $username;
       // redirection vers la page de l'application
        header('Location:appPage');
    }else{
      throw new LoginException("Nom d'utilisateur/Mot de passe incorrect.");
    }
    
  }

  /**
   * Déconnecter un utilisateur
   *
   * @return void
   */
  #[Route(path: "/logout", name: "logout", httpMethod: "GET")]
  public function logout(){
    unset($_SESSION['username']);
    header('Location:/');
  }

}