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

class PlaylistFormAdd extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Playlist',
                'attr' => ['maxlength'=> 100],
                'required' => true,
            ])//titre
            ->add('description', TextareaType::class,[
               'label' => 'Description',
               'attr' => ['rows' => 6],
               'required' => false,
            ])//description
          
            ->add('Ajouter',SubmitType::class);     
    }
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}

