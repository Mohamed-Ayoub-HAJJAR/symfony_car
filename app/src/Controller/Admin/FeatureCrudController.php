<?php

namespace App\Controller\Admin;

use App\Entity\Feature;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

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
}
