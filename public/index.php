<?php

// Inclut l'autoloader généré par Composer
require_once __DIR__ . "/../vendor/autoload.php";

if (
  php_sapi_name() !== 'cli' &&
  preg_match('/\.(?:png|jpg|jpeg|gif|ico)$/', $_SERVER['REQUEST_URI'])
) {
  return false;
}

use App\Config\Connection;
use App\Config\TwigEnvironment;
use App\Controller\IndexController;
use App\Controller\LoginException;
use App\Controller\RegisterException;
use App\DependencyInjection\Container;
use App\Routing\RouteNotFoundException;
use App\Routing\Router;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;

// Env vars - Possibilité d'utiliser le pattern Adapter
// Pour pouvoir varier les dépendances qu'on utilise
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

// BDD
$connection = new Connection();
$entityManager = $connection->init();

// Twig - Vue
$twigEnvironment = new TwigEnvironment();
$twig = $twigEnvironment->init();

// Service Container
$container = new Container();
$container->set(EntityManager::class, $entityManager);
$container->set(Environment::class, $twig);

// Routage
$router = new Router($container);
$router->registerRoutes();

if (php_sapi_name() === 'cli') {
  return;
}

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

session_start();
// si non connecte et pas de demande de creation de compte ou de connexion afficher la page index
if(!isset($_SESSION['username']) && $requestUri != "/registerForm" && $requestUri != "/loginForm" && $requestUri != "/register" && $requestUri != "/login" ){
  $requestUri="/";
  $requestMethod = "GET";
}

// afficher la page demandee
try {
  $router->execute($requestUri, $requestMethod);
} catch (RouteNotFoundException $e) {
  http_response_code(404);
  echo $twig->render('404.html.twig', ['title' => $e->getMessage()]);
}catch(RegisterException $e){
  //page d'inscription 
  echo $twig->render('user/userRegistrationForm.html.twig',['error' => $e->getMessage()]);
}catch(LoginException $e){
  // page de connexion
  echo $twig->render('user/userLoginForm.html.twig',['error' => $e->getMessage()]);
}
