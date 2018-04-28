<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 16/04/2018
 * Time: 10:52
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Prénom*'],
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Nom*'],
            ])
            ->add('mail', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Email*'],
            ])
            ->add('password', RepeatedType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,64}/',
                        'match' => true,
                        'message' => 'Le mot de passe doit contenir entre 8 et 64 caractères, un mélange de majuscules de minuscules et un chiffre.'
                    ])
                ],
                'type' => PasswordType::class,
                'label' => false,
                'first_options' => [
                    'label' => false,
                    'attr' => ['placeholder' => 'Mot de passe*',
                        'class' => 'form-control']
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => ['placeholder' => 'Confirmez votre mot de passe*',
                        'class' => 'form-control']
                ],
                'invalid_message' => 'Les mots de passe doivent être identiques.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', User::class);
    }
}