<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\Picture;
use App\Entity\Video;
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
            ->add('name')
        ;
        $builder->add('images', CollectionType::class, [

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
