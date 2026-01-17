<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Votre Nom',
                'attr' => ['placeholder' => 'Jean Dupont'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre nom']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Votre Prénom',
                'attr' => ['placeholder' => 'Jean Dupont'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre nom']),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse Email',
                'attr' => ['placeholder' => 'jean.dupont@email.com'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un email']),
                    new Email(['message' => 'L\'adresse email n\'est pas valide']),
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Votre Téléphone',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez préciser le sujet']),
                    new Length(['min' => 5, 'minMessage' => 'Le sujet doit faire au moins {{ limit }} caractères']),
                ],
            ])
            ->add('sujet', ChoiceType::class, [
                'choices'  => [
                    'Sélectionnez un sujet' => '',
                    'Demande d\'information' => 'info',
                    'Prise de rendez-vous' => 'rdv',
                    'Demande de financement' => 'financement',
                    'Estimation reprise' => 'reprise',
                    'Autre' => 'autre',
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => ['rows' => 6, 'placeholder' => 'Comment pouvons-nous vous aider ?'],
                'constraints' => [
                    new NotBlank(['message' => 'Le message ne peut pas être vide']),
                    new Length(['min' => 10, 'minMessage' => 'Le message est trop court']),
                ],
            ])
            // On peut ajouter le bouton directement ici ou dans le Twig
            ->add('envoyer', SubmitType::class, [
                'label' => 'Enovoyer le message',
                'attr' => ['class' => 'btn btn-primary w-100 mt-3'],
                'label' => 'Envoyer le message'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Important : on laisse vide car pas d'entité Contact liée
        ]);
    }
}
