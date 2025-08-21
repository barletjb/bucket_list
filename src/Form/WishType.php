<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'attr' => [
                    'maxlength' => 250,
                    'placeholder' => 'Entrez le titre de votre souhait'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Décrivez votre souhait en détail...'
                ]
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur :',
                'attr' => [
                    'maxlength' => 50,
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie :',
                'placeholder' => 'Choisissez une catégorie',
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },

            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Is Published :',
                'required' => false,
                'attr' => [
                    'checked' => true,
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn-soft'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
