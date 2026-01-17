<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\CarRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CarFilterType extends AbstractType
{
    private $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $brands = $this->carRepository->findAllUniqueBrands();

        $builder
            ->add('brand', ChoiceType::class, [
                'choices'  => array_combine($brands, $brands),
                'placeholder' => 'Toutes les marques',
                'required' => false,
                'attr' => ['id' => 'marque'] // On garde ton ID HTML
            ])
            ->add('model', ChoiceType::class, [
                'choices'  => [],
                'placeholder' => 'Choisissez d\'abord une marque',
                'required' => false,
                'attr' => ['id' => 'modele'],
                'extra_fields_message' => 'Ce champ est dynamique',
                'allow_extra_fields' => true, // Permet d'accepter les valeurs ajoutées en JS
            ])
            ->add('maxPrice', ChoiceType::class, [
                'choices' => [
                    '5 000 €' => 5000,
                    '10 000 €' => 10000,
                    '15 000 €' => 15000,
                    '20 000 €' => 20000,
                    '30 000 €' => 30000,
                ],
                'placeholder' => 'Tous les prix',
                'required' => false,
                'attr' => ['id' => 'prix']
            ])
            ->add('minYear', ChoiceType::class, [
                'choices' => array_combine(range(date('Y'), 2010), range(date('Y'), 2010)),
                'placeholder' => 'Toutes années',
                'required' => false,
                'attr' => ['id' => 'annee']
            ])
            ->add('rechercher', SubmitType::class, [
                'label' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                    </svg>Rechercher',
                'label_html' => true, // Très important pour autoriser le SVG
                'attr' => [
                    'class' => 'btn btn-primary search-btn'
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (isset($data['model']) && !empty($data['model'])) {
                // On ajoute la valeur reçue aux choix pour que Symfony l'accepte
                $form->add('model', ChoiceType::class, [
                    'choices' => [$data['model'] => $data['model']],
                    'required' => false,
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET', // Très important pour les filtres
            'csrf_protection' => false,
        ]);
    }
}
