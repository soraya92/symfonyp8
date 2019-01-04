<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleAdminType;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
    * @Route("/test/deny")
    *Pour contrôler plus finement l'accès à nos contrôleurs
    */
    public function testDeny(){
    	//si l'utilisateur n'a pas le ROLE_AUTEUR, une erreur 403 est renvoyée
    	$this->denyAccessUnlessGranted('ROLE_AUTEUR', null, 'page interdite');

    	//si on a le ROLE_AUTEUR, le reste du controleur

    	return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
    *Autre méthode pour restreindre l'accès à un contrôleurs : les annotations
    * @Route("/test/deny2")
    * @Security("has_role('ROLE_AUTEUR')")
    */

    public function testDeny2(){

    	return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);

    }

    /**
    * @Route("/admin/article/add", name="addArticleAdmin")
    */

    public function addArticleAdmin(Request $request)
    {

        $article = new Article();

        $form = $this->createForm(ArticleAdminType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $article = $form->getData();
            //l'auteur de l'article est l'utilisateur connecté
            $article->setUser($this->getUser());
            //je fixe la date de publication de l'article
            $article->setDatePubli(new \DateTime(date('Y-m-d H:i:s')));
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'article ajouté');
            return $this->redirectToRoute('articles');
        
        }

        return $this->render('admin/add.html.twig', ['form' => $form->createView()]);


       
    }
}