<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     * max = 50,
     * maxMessage = "Le titre ne doit pas faire plus de 50 caractères"
     *)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     * min = 10,
     * maxMessage = "Le contenu doit faire plus de 10 caractères"
     *)
     * @Assert\NotBlank()
     */
    private $content;

    /**
    * @ORM\Column(type="datetime")
    * @Assert\DateTime
    */
    private $date_publi;
    /**
    * je décris ma relation
    * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
    */
    private $user;

    /**
    * je décris ma relation
    * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="articles")
    */
    private $categorie;

    /**
    * je décris ma relation
    * @ORM\OneToMany(targetEntity="App\Entity\Commentaires", mappedBy="article")
    */
    private $comments;

    /**
    * @ORM\Column(type="string")
    * @Assert\Image
    */
    private $image;   //pour uploader une image

    public function __construct(){
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDatePubli(): ?\DateTimeInterface
    {
        return $this->date_publi;
    }

    public function setDatePubli(\DateTimeInterface $date_publi): self
    {
        $this->date_publi = $date_publi;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getImage()
    {
        return $this->image;

    }
    public function setImage($image){
        $this->image = $image;
        return $this;
    }

    public function getComments(): Collection{
        return $this->comments;
    }
}
