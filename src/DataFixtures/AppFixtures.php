<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Categorie;
use App\Entity\Article;
use App\Entity\Message;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	//attribut pour stocker l'encoder
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder){
		// lors de l'instanciation, on stocke dans l'attru=ibut encoder, l'objet qui va nous permettre d'encoder les mdp
		$this->encoder = $encoder;
	}
    public function load(ObjectManager $manager)
    {

    	//création de 5 users
        $users = [];

    	for($i=1;$i<=10;$i++){
    		$user = new User();
    		$user->setUsername('Toto' . $i);
    		$user->setEmail('toto' . $i . '@toto.to');
    		if($i === 1){
    			$roles = ['ROLE_USER', 'ROLE_ADMIN'];
    		}
    		else{
    			$roles = ['ROLE_USER'];
    		}

    		$user->setRoles($roles);

    		$plainPassword = 'toto';
    		$mdpencoded = $this->encoder->encodePassword($user, $plainPassword);
    		$user->setPassword($mdpencoded);

    		$manager->persist($user);

            $users[] = $user;
    	}
        // on va créer 10 catégories
        for($i=1;$i<=10;$i++){
        	$categorie = new Categorie();
        	$categorie->setLibelle('categorie' . $i);
        	$categorie->setDescription('description' . $i);
        	$categorie->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));

        	$manager->persist($categorie);

        }

        for($i=1;$i<=10;$i++){
        	$article = new Article();
        	$article->setTitle('article' . $i);
        	$article->setContent('description' . $i);
        	

        	//on va générer des dates aléatoirement

        	$timestamp = mt_rand(1, time());
        	//formatage du timestand en date
        	$randomDate = date('Y-m-d H:i:s', $timestamp);

        	$article->setDatePubli(new \DateTime($randomDate));

        	//array_rand choisit au hasard une clé dans un tableau

            $article->setUser($users[array_rand($users)]);
            $manager->persist($article);

        	//tableau d'auteurs dans lequel on vient piocher au hasard 
        	// $auteurs = ['Verlaine', 'Maupassant', 'Hugo', 'Zola', 'Dumas', 'Molière', 'Shakespeare'];

        	
        }

        for($i=1;$i<=10;$i++){
        	$message = new Message();
        	$message->setSujet('sujet' . $i);
        	$message->setContenu('contenu' . $i);
        	$message->setDateenvoi(new \DateTime(date('Y-m-d H:i:s')));
        	$message->setEmail('email@email.com' . $i);
        	$message->setNom('nom' . $i);

        	$manager->persist($message);

        }

        $manager->flush();
    }
}
