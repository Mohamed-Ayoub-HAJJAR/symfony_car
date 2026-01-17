<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use App\Form\ImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use App\Controller\Admin\FeatureCrudController;

class CarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Car::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('Informations Générales')->setIcon('fas fa-info-circle');
        yield TextField::new('brand', 'Marque')->setColumns(6);
        yield TextField::new('model', 'Modèle')->setColumns(6);
        yield ChoiceField::new('energy', 'Énergie')->setChoices([
            'Essence' => 'essence',
            'Diesel' => 'diesel',
            'Électrique' => 'electrique',
            'Hybride' => 'hybride',
            'GPL' => 'gpl',
        ])->setColumns(4);
        yield MoneyField::new('price', 'Prix')->setCurrency('EUR')->setColumns(4);
        yield IntegerField::new('year', 'Année')->setColumns(2);
        yield IntegerField::new('mileage', 'Kilométrage')->setColumns(2);
    // Section 2 : Caractéristiques Techniques (Tes nouveaux champs)
        yield FormField::addPanel('Caractéristiques Techniques')->setIcon('fas fa-cogs');
        yield IntegerField::new('fiscalPower', 'Puissance Fiscale (CV)')->setColumns(3);
        yield ChoiceField::new('transmission', 'Boîte de vitesse')->setChoices([
            'Manuelle' => 'Manuelle',
            'Automatique' => 'Automatique',
        ])->setColumns(3);

        yield IntegerField::new('gears', 'Nombre de rapports')
            ->setHelp('Exemple: 5, 6, 7...')
            ->setColumns(3);
        yield IntegerField::new('seats', 'Nombre de places')->setColumns(3);
        yield TextField::new('color', 'Couleur')->setColumns(3);
    // Section 3 : Médias et Description
        yield FormField::addPanel('Détails et Photos')->setIcon('fas fa-camera');
        yield TextEditorField::new('description', 'Description')->setColumns(12);
        yield AssociationField::new('features', 'Équipements & Options')
            ->setCrudController(FeatureCrudController::class)
            ->setFormTypeOptions([
                'by_reference' => false, // Obligatoire pour les relations ManyToMany
            ])
            ->autocomplete();
        yield CollectionField::new('images', 'Photos (Max 10)')
            ->setEntryType(ImageType::class)
            ->setFormTypeOption('by_reference', false)
            ->onlyOnForms()
            ->setColumns(12);

    // Champs pour la liste (Index)
        yield IdField::new('id')->hideOnForm();
    }
}
