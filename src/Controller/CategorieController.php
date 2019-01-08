<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategorieType;



class CategorieController extends AbstractController{

     /**
    * Route qui va afficher la liste des categories
     * @Route("/categories", name="categories")
     */
     public function index()
    {
        // récupération de la liste des categories
        // $categorieDB = new CategorieDB();
        // $categories = $categorieDB->findAll();
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repository->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
    * Route qui va afficher la liste des categories
     * @Route("/categorie{id}", name="categorie", requirements={"id"="\d+"});
     */
     public function show(Categorie $categorie)
    {
       
        return $this->render('categorie/categorie.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
    *@Route("/categorie/{id}", name="idCategorie", requirements={"id"="\d+"});
    */

    public function idCategorie($id){
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categorie = $repository->find($id);

        //génération d'une erreur si aucun categorie n'est trouvé
        if(!$categorie){
            throw $this->createNotFoundException('No category found');
        }

        return $this->render('categorie/categorie.html.twig', [
            'categorie' => $categorie,
        ]);
    }
    /**
   	* Route qui va afficher la liste des categories
     * @Route("/categories/add", name="addCategorie")
     *$request contient toutes les informations sur la requête http, notamment en GET et POST
     */


   public function addCategorie(Request $request){
        //pour pouvoir sauvegarder un objet = insérer les infos dans la table, on utilise l'entity manager
        $entityManager = $this->getDoctrine()->getManager();

        //on crée notre objet article, pour l'instant en dur
        $categorie = new Categorie();

        //je crée un objet formulaire qui prend comme modèle l'entité Catégorie
        // $form = $this->createFormBuilder($categorie)
        //                 ->add('libelle', TextType::class)
        //                 ->add('description', TextareaType::class)
        //                 ->add('enregistrer', SubmitType::class)
        //                 ->getForm();


        //je demande au formulaire de gérer la requête
        $form = $this->createForm(CategorieType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // le formulaire a été soumis et validé

            //je crée un objet catégorie à partir des données envoyées

            $categorie = $form->getData();
            $categorie->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));
            //je persiste ma catégorie
            $entityManager->persist($categorie);
            $entityManager->flush();

            $this->addFlash('success', 'catégorie ajoutée');
            return $this->redirectToRoute('categories');
        }

        

        //je passe mon formulaire en paramètre de ma vue
        return $this->render('categorie/add.html.twig', ['form' => $form->createView()]);

        $this->addFlash('success', 'catégorie ajoutée');
        // $categorie->setLibelle('littérature');
        // $categorie->setDescription('fdjfsdqfhjkhdfsjfqsdh');
        // //on doit envoyer un objet de classe datetime puisqu'on a créé notre propriété date_publi au format dateTime
        // $categorie->setDateCreation(new  \DateTime(date('Y-m-d H:i:s')));

        // $entityManager->persist($categorie);


        return $this->render('categorie/add.html.twig',['categories' => $categories
            ]);;
    }



    /**

    *@Route("categorie/update/{id}", name="updateCategorie", requirements={"id"="\d+"})

    */

    public function updateCategorie(Request $request, Categorie $categorie){
        
        //récupération de l'entity manager pour pouvoir faire l'update
        $entityManager = $this->getDoctrine()->getManager();

        //je crée mon formulaire

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $categorie = $form->getData();
            $entityManager->flush();

            $this->addFlash('success', 'categorie modifiée');
            return $this->redirectToRoute('categories');

        }

        return $this->render('categorie/add.html.twig', ['form' => $form->createView()]);
  
    }

        /**
        *@Route("/categorie/delete/{id}", name="deleteCategorie", requirements={"id"="\d+"})
        *Le param converter :on explique à Symfony que l'on veut convertir directement l'id en objet de classe Article en mettant le nom de la classe dans les parenthèses
        */

        public function deleteCategorie(Categorie $categorie){
            // quand j'écris l'objet article dans le paramètre, je n'ai pas besoin de ces lignes : 
            // $repository = $this->getDoctrine()->getRepository(Article::class);
            // $categorie = $repository->find($id);

            // récupération de l'entity manager, nécessaire pour la suppression

            $entityManager = $this->getDoctrine()->getManager();
            //je veux supprimer cet article
            $entityManager->remove($categorie);
            //pour valider la suppression d'un article
            $entityManager->flush();

            //génération d'un message flash
            $this->addFlash('warning', 'categorie supprimée');
            // redirection vers la liste des articles
            return $this->redirectToRoute('categories');


        }

        /**
        *@Route("/categorie/recentes", name="categoriesRecentes")
        */
        public function getLastFive(){
            $repository = $this->getDoctrine()->getRepository(Categorie::class);
            $categories = $repository->getLastFive();

            return $this->render('categorie/recentes.html.twig', [
                'categories' => $categories
            ]);
        }

}