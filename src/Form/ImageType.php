<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 29/03/2018
 * Time: 13:17
 */

namespace App\Form;


use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'error_bubbling' => false,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Image()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Image::class);
    }
}