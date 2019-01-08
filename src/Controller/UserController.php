<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
   
    	$repository = $this->getDoctrine()->getRepository(User::class);
    	$users = $repository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
   * @Route("user/user/{id}", name="showUser", requirements={"id"="\d+"})
   */
   public function showUser(User $user) {
       return $this->render('user/user.html.twig', ['user'=>$user]);
   }
}
