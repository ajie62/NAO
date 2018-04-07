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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            ->add('image', ImageType::class, ['required' => false])
            ->add('capture', ImageType::class, ['attr' => ['capture' => 'camera']])
            ->add('species', TextType::class, ['label' => 'Espèce'])
            ->add('longitude', HiddenType::class)
            ->add('latitude', HiddenType::class)
            ->add('sex', ChoiceType::class, [
                'label' => 'Sexe',
                'choices' => [
                    'Indéfini' => 'Indéfini',
                    'Mâle' => 'Mâle',
                    'Femelle' => 'Femelle'
                ]
            ])
            ->add('atlasCode', ChoiceType::class, [
                'label' => 'Code atlas',
                'choices' => $this->getAtlasCodes(),
            ])
            ->add('deceased', CheckboxType::class, [
                'label' => 'Animal décédé',
                'required' => false,
            ])
            ->add('deathCause', ChoiceType::class, [
                'label' => 'Cause de la mort',
                'choices' => $this->getDeathCauses(),
            ])
            ->add('flightDirection', ChoiceType::class, [
                'label' => 'Direction du vol',
                'choices' => $this->getFlightDirections(),
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

    /**
     * Returns an array of death causes
     * @return array
     */
    private function getDeathCauses()
    {
        return [
            'Indéfinie' => null,
            'Abattu' => 'Abattu',
            'Percuté par un véhicule' => 'Percuté par un véhicule',
            'Empoisonné' => 'Empoisonné',
            'Tué par un autre animal' => 'Tué par un autre animal'
        ];
    }

    /**
     * Returns an array of flight directions
     * @return array
     */
    private function getFlightDirections()
    {
        return [
            'Indéfinie' => null,
            'Au sol' => 'Au sol',
            'Nord' => 'N',
            'Nord-Nord-Est' => 'N.N.E',
            'Nord-Est' => 'N.E',
            'Est-Nord-Est' => 'E.N.E',
            'Est' => 'E',
            'Est-Sud-Est' => 'E.S.E',
            'Sud-Est' => 'S.E',
            'Sud-Sud-Est' => 'S.S.E',
            'Sud' => 'S',
            'Sud-Sud-Ouest' => 'S.S.O',
            'Sud-Ouest' => 'S.O',
            'Ouest-Sud-Ouest' => 'O.S.O',
            'Ouest' => 'O',
            'Ouest-Nord-Ouest' => 'O.N.O',
            'Nord-Ouest' => 'N.O',
            'Nord-Nord-Ouest' => 'N.N.O'
        ];
    }

    /**
     * Returns a list of atlas codes
     * @see http://files.biolovision.net/haute-savoie.lpo.fr/userfiles/Utilisationdescodesatlasfaune-drme.pdf
     * @return array
     */
    private function getAtlasCodes()
    {
        return [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
    }
}