<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 25/04/2018
 * Time: 06:45
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Ancien mot de passe'],
                'constraints' => [ new NotBlank() ]
            ])
            ->add('newPasswordFirst', PasswordType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Nouveau mot de passe'],
                'constraints' => [ new NotBlank() ]
            ])
            ->add('newPasswordSecond', PasswordType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Confirmer le nouveau mot de passe'],
                'constraints' => [ new NotBlank() ]
            ]);
    }
}