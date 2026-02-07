<?php

namespace App\Controller\Admin;

use App\Entity\Feature;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class FeatureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Feature::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom de l\'équipement');

        yield ChoiceField::new('category', 'Catégorie')
            ->setChoices([
                'Sécurité' => Feature::CAT_SECURITY,
                'Confort' => Feature::CAT_COMFORT,
                'Multimédia' => Feature::CAT_MULTIMEDIA,
                'Extérieur' => Feature::CAT_EXTERIOR,
            ]);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setLabel('Ajouter un équipement')
                    ->setIcon('fa fa-plus-circle');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action
                    ->setLabel('Enregistrer l\'équipement')
                    ->setCssClass('btn');
            })
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action
                    ->setLabel('Mettre à jour l\'équipement');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Titre de la page de liste
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des équipements')
            // Titre de la page de création
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un nouvel équipement')
            // Titre de la page d'édition
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier l\'équipement')
            // Vous pouvez aussi changer le nom de l'entité partout
            ->setEntityLabelInSingular('Équipement')
            ->setEntityLabelInPlural('Équipements');
    }
}
