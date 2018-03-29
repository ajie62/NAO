<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 28/03/2018
 * Time: 12:35
 */

namespace App\Form;


use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('articleField', FroalaEditorType::class, [
               'language' => 'fr',
               'toolbarInline' => false,
                'placeholderText' => 'Tapez quelque chose'
            ]);
        ;
    }
}