<?php
// src/Form/ProductType.php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\ProductStatus; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('name', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le nom ne peut pas être vide.']),
            ],
        ])
        ->add('image_file', FileType::class, [
            'mapped' => false, 
            'required' => false,
            'label' => 'Image (fichier)',
            'constraints' => [
                new Assert\File([
                    'maxSize' => '2M', 
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Veuillez téléverser une image valide (JPEG ou PNG).',
                ]),
            ],
        ])
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
            'label' => 'Catégorie',
        ])
        ->add('price', NumberType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le prix ne peut pas être vide.']),
                new GreaterThan([
                    'value' => 0,
                    'message' => 'Le prix doit être supérieur à zéro.',
                ]),
            ],
        ])
        ->add('description')
        ->add('stock', NumberType::class, [
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Positive()
            ]
        ])
        ->add('status', EnumType::class, [
            'class' => ProductStatus::class, 
        ]);
}
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
?>