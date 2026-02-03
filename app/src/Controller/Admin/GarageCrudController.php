<?php

namespace App\Controller\Admin;

use App\Entity\Garage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class GarageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Garage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Identifiant technique (caché dans les formulaires)
        yield IdField::new('id')->hideOnForm();

        // --- SECTION 1 : IDENTITÉ ADMINISTRATIVE ---
        yield FormField::addPanel('Informations Légales')
            ->setIcon('fa fa-id-card');

        yield TextField::new('nom', 'Nom du Garage')
            ->setColumns('col-md-6')
            ->setHelp('Le nom commercial qui apparaîtra sur le site');

        yield TextField::new('siret', 'Numéro SIRET')
            ->setColumns('col-md-3')
            ->setFormTypeOptions([
                'attr' => [
                    'maxlength' => 14,      // Bloque la saisie clavier à 14
                    'minlength' => 14,      // Optionnel: demande exactement 14
                    'placeholder' => '12345678900012',
                    'pattern' => '[0-9]{14}' // N'autorise que les chiffres
                ]
            ]);

        yield TextField::new('directeur', 'Directeur de publication')
            ->setColumns('col-md-3')
            ->setHelp('Nom de la personne responsable (ex: Vincent Parrot)');

        // --- SECTION 2 : COORDONNÉES ---
        yield FormField::addPanel('Coordonnées du Garage')
            ->setIcon('fa fa-building');

        yield TextField::new('adresse', 'Adresse postale')
            ->setColumns('col-md-6');

        yield TelephoneField::new('telephone', 'Téléphone')
            ->setColumns('col-md-3')
            ->setHelp('Format : 0123456789');

        yield EmailField::new('email', 'Email de contact')
            ->setColumns('col-md-3');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setLabel('Ajouter un garage')
                    ->setIcon('fa fa-plus-circle');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action
                    ->setLabel('Enregistrer le garage')
                    ->setCssClass('btn');
            })
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action
                    ->setLabel('Mettre à jour le garage');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Titre de la page de liste
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des garage')
            // Titre de la page de création
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un nouveau garage')
            // Titre de la page d'édition
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier le garage')
            // Vous pouvez aussi changer le nom de l'entité partout
            ->setEntityLabelInSingular('garage')
            ->setEntityLabelInPlural('garages');
    }
}
