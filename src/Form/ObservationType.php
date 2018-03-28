<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 27/03/2018
 * Time: 18:01
 */

namespace App\Form;

use App\Entity\Observation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('species', TextType::class, ['label' => 'Espèce'])
            ->add('longitude', TextType::class, ['label' => 'Longitude'])
            ->add('latitude', TextType::class, ['label' => 'Latitude'])
            ->add('birdNumber', IntegerType::class, [
                'label' => "Nombre d'oiseaux",
                'attr' => ['min' => 0]
            ])
            ->add('sex', ChoiceType::class, [
                'label' => 'Sexe',
                'choices' => [
                    'Indéfini' => 'Indéfini',
                    'Mâle' => 'Mâle',
                    'Femelle' => 'Femelle'
                ]
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Âge',
                'required' => false,
            ])
            ->add('atlasCode', ChoiceType::class, [
                'label' => 'Code atlas',
                'choices' => [
                    'Aucun' => 'Aucun',
                    '1 : description' => '1 : description',
                    '2 : description' => '2 : description',
                    '3 : description' => '3 : description',
                    '4 : description' => '4 : description',
                ]
            ])
            ->add('deceased', CheckboxType::class, [
                'label' => 'Animal décédé',
                'required' => false,
            ])
            ->add('deathCause', ChoiceType::class, [
                'label' => 'Cause de la mort',
                'choices' => [
                    'Inconnue' => 'Inconnue',
                    'Abattu' => 'Abattu',
                    'Percuté par un véhicule' => 'Percuté par un véhicule',
                    'Empoisonné' => 'Empoisonné',
                    'Tué par un autre animal' => 'Tué par un autre animal'
                ]
            ])
            ->add('flightDirection', ChoiceType::class, [
                'label' => 'Direction du vol',
                'choices' => [
                    'Inconnue' => 'Inconnue',
                    'Nord' => 'Nord',
                    'Nord-Est' => 'Nord-Est'
                ]
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Observation::class);
    }
}