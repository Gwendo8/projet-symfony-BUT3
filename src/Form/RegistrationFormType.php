<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('email', EmailType::class, [
            'attr' => ['class' => 'form-control'], 
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('firstname', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('lastname', TextType::class, [
            'attr' => ['class' => 'form-control'], 
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('plainPassword', PasswordType::class, [
            'label' => 'Password',
            'mapped' => false,
            'attr' => ['class' => 'form-control', 'autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit contenir au moins 6 caractères',
                ]),
            ],
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}