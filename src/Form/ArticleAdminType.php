<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;

class ArticleAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('label' => 'Titre de l\'article'))
            ->add('content', TextareaType::class, array('label' => 'Contenu de l\'article'))
            ->add('date_publi', DateTimeType::class, array('label' => 'Date de création'))
            ->add('enregistrer', SubmitType::class, array('attr' =>['class'=> 'btn-primary']))
            ->add('users', EntityType::class, array(
            // looks for choices from this entity
            'class' => User::class,

            // uses the User.username property as the visible option string
            'choice_label' => 'username',
));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}