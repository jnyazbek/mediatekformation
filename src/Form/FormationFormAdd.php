<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationFormAdd extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('title',TextType::class,[
                'label'=>'Titre',
                'attr' => ['maxlength'=> 100],
                'required' => true,
            ])//titre
            ->add('publishedAt', DateTimeType::class, [
                'widget'=>'single_text',
                'label' => 'Date de publication',
                'required'=>true,
                ])//date de publication
            ->add('description', TextareaType::class,[
               'label' => 'Description',
               'attr' => ['rows' => 6],
               'required' => false,
            ])//déscription
            ->add('playlist',EntityType::class, [
                'class' => Playlist::class,
                'choice_label'=> 'name',
                'required' => true,
                ])// sélecteur de playlists
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])// sélecteur de catégories
             ->add('miniature', TextType::class, [
               'label' => 'Miniature URL',
               'attr' => ['maxlength' => 100],
               'required' => false,
            ])
            ->add('picture', TextType::class, [
               'label' => 'Image URL',
               'attr' => ['maxlength' => 100],
               'required' => false,
            ])
            ->add('videoId', TextType::class, [
               'label' => 'Video ID',
               'attr' => ['maxlength' => 11],
               'required' => false, 
            ])
            ->add('Ajouter',SubmitType::class);
            
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}

