<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 25/04/2018
 * Time: 03:18
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', ImageType::class,[
                'required' => false
            ])
            ->add('mail', EmailType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez indiquer une adresse mail.'
                    ])
                ]
            ])
            ->add('introduction', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 700,
                        'maxMessage' => 'Votre description ne peut pas faire plus de 700 caractères.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', User::class);
    }
}