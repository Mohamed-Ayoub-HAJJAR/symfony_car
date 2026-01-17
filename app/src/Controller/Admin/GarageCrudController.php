<?php

namespace App\Controller\Admin;

use App\Entity\Garage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class GarageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Garage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // On affiche l'ID uniquement sur la page Index (liste)
        yield IdField::new('id')->hideOnForm();

        // Création d'une section visuelle
        yield FormField::addPanel('Coordonnées du Garage')
            ->setIcon('fa fa-building');

        // Champ Adresse
        yield TextField::new('adresse', 'Adresse postale')
            ->setColumns('col-md-6');

        // Champ Téléphone avec formatage mobile
        yield TelephoneField::new('telephone', 'Téléphone')
            ->setColumns('col-md-3')
            ->setHelp('Format : 0123456789');

        // Champ Email avec validation automatique
        yield EmailField::new('email', 'Email de contact')
            ->setColumns('col-md-3');
    }
}
