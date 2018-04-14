<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 27/03/2018
 * Time: 18:01
 */

namespace App\Form;

use App\Entity\Observation;
use App\Entity\Species;
use App\Service\ObsTypeChoices;
use App\Validator\Constraints\HasImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class ObservationType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', ImageType::class, [
                'required' => false,
                'label' => false,
                'error_bubbling' => false,
                'constraints' => [
                    new HasImage()
                ]
            ])
            ->add('espece', TextType::class, [
                'label' => 'Espèce',
                'mapped' => false,
                'attr' => ['autocomplete' => 'off']
            ])
            ->add('species', HiddenType::class, [
                'attr' => ['class' => 'js-species']
            ])
            ->add('longitude', HiddenType::class)
            ->add('latitude', HiddenType::class)
            ->add('sex', ChoiceType::class, [
                'label' => 'Sexe',
                'choices' => $options['choices_data']['genders'],
            ])
            ->add('atlasCode', ChoiceType::class, [
                'label' => 'Code atlas',
                'choices' => range(0,19)
            ])
            ->add('deceased', CheckboxType::class, [
                'label' => 'Animal décédé',
                'required' => false,
            ])
            ->add('deathCause', ChoiceType::class, [
                'label' => 'Cause de la mort',
                'choices' => $options['choices_data']['deathCauses']
            ])
            ->add('flightDirection', ChoiceType::class, [
                'label' => 'Direction du vol',
                'choices' => $options['choices_data']['flightDirections']
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
        ;

        $builder
            ->get('species')->addModelTransformer(new CallbackTransformer(
                function($data){
                    return null;
                },
                function($data){
                    if ($data) {
                        $species = $this->entityManager->getRepository(Species::class)->find($data);
                        return $species;
                    }
                    return null;

                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Observation::class);
        $resolver->setRequired('choices_data');
    }
}
