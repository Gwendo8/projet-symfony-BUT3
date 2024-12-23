<?php
namespace App\Form;

use App\Entity\CreditCard;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
class CreditCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, [
                'label' => 'Numéro de carte',
                'required' => true,
                'attr' => [
                    'maxlength' => 16,
                    'placeholder' => 'Entrez 16 chiffres',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le numéro de carte est obligatoire.'),
                    new Assert\Length(
                        min: 16,
                        max: 16,
                        minMessage: 'Le numéro de carte doit être composé de 16 chiffres.',
                        maxMessage: 'Le numéro de carte doit être composé de 16 chiffres.',
                    ),
                ],

            ])
            ->add('expirationDate', DateType::class, [
                'data' => new \DateTime(),
                'constraints' => [
                    new Assert\NotBlank(message: 'La date dexpiration est obligatoire.'),
                    new Assert\Type(type: \DateTimeInterface::class, message: 'Veuillez entrer une date valide.'),
                ],

            ])
            ->add('cvv', TextType::class, [
                'label' => 'CVV',
                'required' => true,
                'attr' => ['maxlength' => 3],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le code doit être dau moins 3 caractères'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreditCard::class,
        ]);
    }
}
?>