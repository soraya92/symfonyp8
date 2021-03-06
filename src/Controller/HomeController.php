<?php

// je range toutes mes classes de controleurs dans le namespace App/Controller
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
//pour pouvoir utiliser les annotations
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Créer une page  pour l'url /exercice1/comment-allez-vous, qui affiche "bien, merci"

//pour pouvoir utiliser la méthode render et autres méthodes utiles
// on hérite de la classe AbstractController
class HomeController extends AbstractController{

	// déclaration de notre méthode / controleur
	/**
	*grâce aux annotations, je peux déclarer ma route
	*@Route("/bonjour", name="bonjour")
	*/
	public function bonjour(){
		return new Response('<html><body><strong>Bonjour</strong></body></html>');

	}
	/**
	*@Route("/exercice1/comment-allez-vous", name="merci")
	*/
	public function merci(){
		return new Response('<html><body><strong>bien,merci</strong></body></html>');

	}



	/**
	*@Route("/", name="home")
	*/
	public function home(){

		$pseudo = 'toto';
		//Symfony va chercher les vues dans /templates
		// je peux passer des variables en paramètre à ma vue twig
		// grâce à un tableau qui contient en clé les noms des paramètres et en valeur leurs valeurs
		// sur index.html.twig la variable nom accessible
		
		//Symfony va chercher les vues dans /templates
		return $this->render('index.html.twig', array('nom' => $pseudo) );
	}


	/**
	*@Route("/exercice2/heure", name="heure")
	*/

	public function heure(){

   		$heure = date('H\hi');
   		$date = date('d/m/Y');

		return $this->render('exercice.html.twig', array('date' => $date, 'heure' => $heure) );

	}

	/**
	*on peut contrôler ce que va contenir le placeholder avec une regex
	*@Route("/bonjour/{nom}", name="bonjourNom", requirements={"nom"="[a-z]+"})
	*/
	public function bonjourPseudo($nom){
		//$nom est automatiquement envoyé en paramètre de notre méthode et contiendra tout ce qui suit bonjour/
		return $this->render('bonjour.html.twig', array('pseudo' => $nom));

	}

	/**
	* méthode qui va faire une redirection  vers la page d'accueil
	*@Route("/redirect")
	*
	*/
	public function redirectHome(){
		//pour faire une redirection en paramètre le nom de la route vers laquelle on veut rediriger
		return $this->redirectToRoute('home');
	}

	/**
	*on peut contrôler ce que va contenir le placeholder avec une regex
	*@Route("/exercice3/{age}/{nom}", name="pseudoAge", requirements={"nom"="[a-z]+", "age"="\d+"})
	*/
	public function pseudoAge($age,$nom){
		//$nom est automatiquement envoyé en paramètre de notre méthode et contiendra tout ce qui suit bonjour/
		return $this->render('exercice3.html.twig', array('age' => $age, 'pseudo' => $nom));

	}


	// 	Créer une page  pour les url de type /exercice3/25/toto

	// Ou 25 est un placeholder qui repr�sente un age (donc uniquement des chiffres) et toto un pseudo (donc uniquement des lettres)
	// Créer une vue (exercice3.html.twig) qui va afficher, Bonjour 'pseudo' tu as 'age' ans
	// Mettre an au singulier si age = 1


	/** 
	* Page test pour accéder à get ou post
	* @Route("/test-get", name="test-get")

*/
	public function testGet(Request $request){
		//pour accéder à $_GET
		$get = $request->query->all();
		//$_POST
		$post = $request->request->all(); 
		//$_FILES
		$files = $request->files->all(); 
		// si j'attends un paramètre message ?message=jfhksjdfh
		$message = $request->query->get('message', 'pas de message');

		dump($get);
		return $this->render("test.request.html.twig", ['message' => $message]);
	}
}