<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;
use DateTime;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractController
{
  #[Route(path: "/")]
  public function index(EntityManager $em)
  {
   /* $user = new User();

    $user->setUsername("Bobby")
      ->setPassword("randompass")
      ->setEmail("bob@bob.com");

    // On demande au gestionnaire d'entités de persister l'objet
    // Attention, à ce moment-là l'objet n'est pas encore enregistré en BDD
    $em->persist($user);
    $em->flush();*/


    /*if($_SESSION['username'] === null){
      $message = "Vous n'êtes pas connecté.";
    }else{
      $message = "Vous êtes connecté.";
    }*/
    echo $this->twig->render('index.html.twig');
    
  }

  #[Route(path: "/appPage", name: "appPage", httpMethod: "GET")]
  public function appPage()
  {
    echo $this->twig->render('appPage.html.twig');
  }

  #[Route(path: "/contact", name: "contact", httpMethod: "POST")]
  public function contact()
  {
    echo $this->twig->render('index/contact.html.twig');
  }
}
