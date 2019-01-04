<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleUserType;

class ArticleController extends AbstractController{
    /**
   	* Route qui va afficher la liste des articles
     * @Route("/articles", name="articles")
     */
    public function index()
    {
    	// récupération de la liste des articles
    	// $articleDB = new ArticleDB();
    	//$articles = $articleDB->findAll();
    	$repository = $this->getDoctrine()->getRepository(Article::class);
    	$articles = $repository->myFindAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
    *@Route("/article/{id}", name="idArticle", requirements={"id"="\d+"});
    */

    public function idArticle($id){
    	$repository = $this->getDoctrine()->getRepository(Article::class);
    	$article = $repository->find($id);

    	//génération d'une erreur si aucun article n'est trouvé
    	if(!$article){
    		throw $this->createNotFoundException('No article found');
    	}

    	return $this->render('article/article.html.twig', [
            'article' => $article,
        ]);
    }

    /**
    *@Route("/article/add", name="addArticle")
    */

    public function addArticle(Request $request){

        //seul un utilisteur connecté peut ajouter un article
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    	//pour pouvoir sauvegarder un objet = insérer les infos dans la table, on utilise l'entity manager
    	$entityManager = $this->getDoctrine()->getManager();

    	//on crée notre objet article, pour l'instant en dur
    	$article = new Article();
    	// $article->setTitle('mon premier article');
    	// $article->setContent('fdjfsdqfhjkhdfsjfqsdh');
    	// //on doit envoyer un objet de classe datetime puisqu'on a créé notre propriété date_publi au format dateTime
    	// $article->setDatePubli(new  \DateTime(date('Y-m-d H:i:s')));
    	// $article->setAuthor('Moi');


    	$form = $this->createForm(ArticleUserType::class);
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

        return $this->render('article/add.html.twig', ['form' => $form->createView()]);

        $this->addFlash('success', 'article ajoutée');

        return $this->render('article/add.html.twig',['articles' => $articles
            ]);;
    }

    /**
    *@Route("/article/recent", name="showRecentArticles")
    */

    public function showRecent(){
    	$repository = $this->getDoctrine()->getRepository(Article::class);
    	$articles = $repository->findAllPostedAfter('2000-01-01');
    	//requête objet :articles2 est un tableau d'objets
    	$articles2 = $repository->findAllPostedAfter2('2000-01-01');
    	return $this->render('article/recent.html.twig', ['articles'=>$articles, 'articles2' => $articles2]);
    }

    /**

	*@Route("article/update/{id}", name="updateArticle", requirements={"id"="\d+"})

	*/

	public function updateArticle(Request $request, Article $article){


		$entityManager = $this->getDoctrine()->getManager();

        //je crée mon formulaire

        $form = $this->createForm(ArticleUserType::class, $article);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $article = $form->getData();

            $article->setUser($this->getUser());
            $entityManager->flush();

            $this->addFlash('success', 'article modifié');
            return $this->redirectToRoute('articles');

        }

        return $this->render('article/add.html.twig', ['form' => $form->createView()]);
  
    }


	    // $repository = $this->getDoctrine()->getRepository(Article::class);
	    // $article = $repository->find($id);


	    // if(!$article){
	    //     throw $this->createNotFoundException('no article found');
	    // }

	    // $article->setContent('contenu modifié');

	    // //récupération de l'entity manager pour pouvoir faire l'update
	    // $entityManager = $this->getDoctrine()->getEntityManager();
	    // //pas besoin de faire->persist($article) car l'article a été récupéré de la base, doctrine le connaît déjà
	    // $entityManager->flush();

	    // //création d'un message flash : stocké dans la session il sera supprimé dès qu'il sera affiché : donc affiché qu'une seule fois
	    // $this->addFlash('success', 'article modifié');

	    // //je redirige vers la page détails de l'article
	    // return $this->redirectToRoute('idArticle', ['id'=>$article->getId()]);
	    // }


	    /**
	    *@Route("/article/delete/{id}", name="deleteArticle", requirements={"id"="\d+"})
	    *Le param converter :on explique à Symfony que l'on veut convertir directement l'id en objet de classe Article en mettant le nom de la classe dans les parenthèses
	    */

	    public function deleteArticle(Article $article){
	    	// quand j'écris l'objet article dans le paramètre, je n'ai pas besoin de ces lignes : 
	    	// $repository = $this->getDoctrine()->getRepository(Article::class);
	    	// $article = $repository->find($id);

	    	// récupération de l'entity manager, nécessaire pour la suppression

	    	$entityManager = $this->getDoctrine()->getManager();
	    	//je veux supprimer cet article
	    	$entityManager->remove($article);
	    	//pour valider la suppression d'un article
	    	$entityManager->flush();

	    	//génération d'un message flash
	    	$this->addFlash('warning', 'Article supprimé');
	    	// redirection vers la liste des articles
	    	return $this->redirectToRoute('articles');


	    }


    
}
