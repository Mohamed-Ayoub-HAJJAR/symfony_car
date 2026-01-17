<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Car;
use App\Entity\Feature;
use App\Entity\Garage;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        // Une séparation pour y voir clair
        yield MenuItem::section('Catalogue');
        // Le lien vers la gestion des voitures
        yield MenuItem::linkToCrud('Mon Garage', 'fa fa-warehouse', Garage::class);
        yield MenuItem::linkToCrud('Gestion des Voitures', 'fas fa-car', Car::class);
        yield MenuItem::linkToCrud('Équipements', 'fa fa-list-check', Feature::class);
        yield MenuItem::section('Administration');
    }
}
