<?php
// src/Form/ProductType.php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\ProductStatus; // Assurez-vous d'importer la bonne énumération

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