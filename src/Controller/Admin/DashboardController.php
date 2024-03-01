<?php

namespace App\Controller\Admin;

use App\Entity\DeliveryCities;
use App\Entity\Category;
use App\Entity\Formules;
use App\Entity\Order;
use App\Entity\Producers;
use App\Entity\Product;
use App\Entity\TimeSlots;
use App\Entity\User;
use App\Entity\WorkingDay;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/admin.html.twig');
        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Brunch Go');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-list', Category::class);
        yield MenuItem::linkToCrud('Produits', 'fa fa-fish', Product::class);
        yield MenuItem::linkToCrud('Formules', 'fa fa-store', Formules::class);
        yield MenuItem::linkToCrud('Producteurs', 'fa fa-address-card', Producers::class);
        yield MenuItem::linkToCrud('Villes de livraison', 'fa fa-city', DeliveryCities::class);
        yield MenuItem::linkToCrud('Jours et heures de livraison', 'fa fa-clock', WorkingDay::class);
        yield MenuItem::linkToCrud('heures disponible', 'fa fa-clock', TimeSlots::class);
        yield MenuItem::linkToCrud('Détails des commandes', 'fa fa-barcode', Order::class);
        
        yield MenuItem::linkToUrl('Revenir au site', 'fa fa-arrow-left', '/');
    }


// public function __construct(
//     private ChartBuilderInterface $chartBuilder,
// ) {
// }

// // ... you'll also need to load some CSS/JavaScript assets to render
// // the charts; this is explained later in the chapter about Design

// #[Route('/admin')]
// public function index(): Response
// {
//     $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
//     // ...set chart data and options somehow

//     return $this->render('admin/my-dashboard.html.twig', [
//         'chart' => $chart,
//     ]);
// }
}