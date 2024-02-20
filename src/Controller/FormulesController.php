<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Formules;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\PersonnalisationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FormulesController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-formules', name: 'app_formules')]
    public function index(): Response
    {
        $formules = $this->entityManager->getRepository(Formules::class)->findAll();
        
        return $this->render('formules/index.html.twig', [
            'formules' => $formules,
        ]);
    }

    #[Route("/formule/{slug}", name: 'formule')]

//     public function show(Request $request, $slug): Response
//     {   
//         $formule = $this->entityManager->getRepository(Formules::class)->findOneBySlug($slug);
//         $category = $this->entityManager->getRepository(Category::class)->findAll();
//         $products = $this->entityManager->getRepository(Product::class)->findAll();
//         if (!$formule) {
//             return $this->redirectToRoute('app_formules');
//         }
        
//         // dd($products);
//         $order = new Order();
//         $form = $this->createForm(PersonnalisationType::class, $order, [
//             'formule' => $formule, 
//             'category' => $category,
//             'products' => $products,
//         ]);

//         $form->handleRequest($request);
//         if ($form->isSubmitted() && $form->isValid()) {
//             $order = $form->getData();
//             $order->setCreatedAt(new \DateTimeImmutable());
//             $this->entityManager->persist($order);
//             $this->entityManager->flush();

//             // Redirection vers une autre page, par exemple la page de confirmation
//             return $this->redirectToRoute('app_cart');
//         }
//         return $this->render('formules/show.html.twig', [
//             'formule' => $formule,
//             'category' => $category,
//             'products' => $products,
//             'form' => $form->createView()
//         ]);
//     }
// }
// src/Controller/FormulesController.php



public function show(Request $request, $slug): Response
{
    $formule = $this->entityManager->getRepository(Formules::class)->findOneBySlug($slug);
    if (!$formule) {
        return $this->redirectToRoute('app_formules');
    }

    $categories = $formule->getCategory();
    $products = [];
    foreach ($categories as $category) {
        $products[] = $category->getProducts()->toArray();
    }
    $products = array_merge(...$products);

    $order = new Order();
    $form = $this->createForm(PersonnalisationType::class, $order, [
        'formule' => $formule,
        'products' => $products,
    ]);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // Code de traitement des données du formulaire
    }

    return $this->render('formules/show.html.twig', [
        'formule' => $formule,
        'form' => $form->createView(),
    ]);
}
}
