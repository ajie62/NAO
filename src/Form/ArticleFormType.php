<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 28/03/2018
 * Time: 12:35
 */

namespace App\Form;


use App\Entity\Article;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('image', ImageType::class)
            ->add('content', FroalaEditorType::class, [
               'language' => 'fr',
               'toolbarInline' => false,
               'placeholderText' => 'Tapez quelque chose'
            ]);
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Article::class);
    }
}