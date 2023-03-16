<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Plant;
use App\Entity\User;


class PlantController extends AbstractDashboardController
{

    public function __construct(private AdminUrlGenerator $adminUrlGenerator) {

    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
        ->setController(PlantCrudController::class)
        ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cherche & Trouve');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Gérer les plantes');
        yield MenuItem::subMenu('Actions', 'fas fa-bar')->setSubItems([
            MenuItem::linkToCrud('Voir les plantes', 'fas fa-eye', Plant::class),
            MenuItem::linkToCrud('Ajouter une plante', 'fa fa-plus', Plant::class)->SetAction(Crud::PAGE_NEW)
        ]);
        yield MenuItem::section('Gérer les utilisateurs');
        yield MenuItem::subMenu('Actions', 'fas fa-bar')->setSubItems([
            MenuItem::linkToCrud('Voir les utilisateurs', 'fas fa-eye', User::class),
            MenuItem::linkToCrud('Ajouter un utilisateur', 'fas fa-plus', User::class)->SetAction(Crud::PAGE_NEW)
        ]);
        yield MenuItem::section('Gérer les photos');
        yield MenuItem::subMenu('Actions', 'fas fa-bar')->setSubItems([
            MenuItem::linkToCrud('Voir les photos', 'fas fa-eye', Image::class),
            MenuItem::linkToCrud('Ajouter une photo', 'fa fa-plus', Image::class)->SetAction(Crud::PAGE_NEW)
        ]);
    }
}
