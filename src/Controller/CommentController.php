<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commentaires;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/delete/{id}", name="deleteComment")
     */
    public function delete(Commentaires $comment)
    {
    	$this->denyAccessUnlessGranted('delete', $comment);

    		//je récupère l'id de l'article associé pour la redirection
    	$idArticle = $comment->getArticle()->getId();
    	$entityManager = $this->getDoctrine()->getManager();
    	$entityManager->remove($comment);
    	$entityManager->flush();
    	$this->addFlash('danger', 'commentaire supprimé');
    	return $this->redirectToRoute('idArticle', ['id' => $idArticle]);

    }
}
