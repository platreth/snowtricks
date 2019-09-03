<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\Picture;
use App\Entity\Video;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'label' => 'Nom de la figure'
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'attr' => ['class' => 'mdb-select md-form colorful-select dropdown-primary'],
                'class' => Category::class,
                'choice_label' => 'name'
        ])
        ->add('images', CollectionType::class, [
            'label' => 'Images(s)',
            'entry_type' => FileType::class,
            'entry_options' => array(
                'constraints'  => array(
                  new File(['maxSize' => '2M',
                  "maxSizeMessage" => "Votre document ne doit pas dépasser les 2 Mo.",
                  "mimeTypes" => ["image/jpeg", "image/png"],
                  "mimeTypesMessage" => "Le document doit avoir une des extensions suivantes : jpeg, png, jpg.",
                        ]),
                   ),
                ),
               'prototype_name' => '__images__',
               'prototype'      => true,
               'allow_add'     => true,
               'allow_delete'   => true,
               'required' => true,
               'label' => 'image',

        ])
            ->add('videos', CollectionType::class, [
            'label' => 'Video(s)',
            'entry_type' => FileType::class,
            'entry_options' => array(
                'constraints'  => array(
                  new File(['maxSize' => '10M',
                  "maxSizeMessage" => "Votre document ne doit pas dépasser les 10 Mo.",
                  "mimeTypes" => ["video/mp4", "video/3gpp"],
                  "mimeTypesMessage" => "Le document doit avoir une des extensions suivantes : mp4, 3gpp.",
                        ]),
                   ),
                ),
               'prototype_name' => '__videos__',
               'prototype'      => true,
               'allow_add'     => true,
               'allow_delete'   => true,
               'required' => true,
               'label' => 'video',

        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'csrf_protection'   => false,

        ]);
    }
}
