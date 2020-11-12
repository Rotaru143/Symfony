<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HelloController
 {
     /**
      * @Route("/hello")
      
     public function sayHello()
     {
       return new Response ('Hello!');
      
     }*/
       public function sayHello()
     {
       return new Response ('Simple! Easy! Beautiful!');
      
     }
  
}