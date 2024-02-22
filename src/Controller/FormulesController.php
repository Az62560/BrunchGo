<?php
namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Category;
use App\Entity\Formules;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\PersonnalisationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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

//     #[Route("/formule/{slug}", name: 'formule')]

//     public function personnalisation($slug): Response
//     {
        
//         $formule = $this->entityManager->getRepository(Formules::class)->findOneBySlug($slug);
        
//         if (!$formule) {
//             return $this->redirectToRoute('app_formules');
//         }
    
//         $categories = $formule->getCategory();
//         $products = [];

//         foreach ($categories as $category) {
//             $categoryProducts = $category->getProducts()->toArray();
//             $products = array_merge($products, $categoryProducts);
//         }
    
//         $cart = new Cart($this->entityManager, $requestStack);
//         $form = $this->createForm(PersonnalisationType::class, $cart, [
//             'formule' => $formule,
//             'products' => $products,
//         ]);

//         $form->handleRequest($requestStack);
//         if ($form->isSubmitted() && $form->isValid()) {
//             $order = $form->getData();
//             $order->setCreatedAt(new \DateTimeImmutable());
//             $this->entityManager->persist($order);
            
//             // $this->entityManager->flush();
//             // dd($order);
//             // Redirection vers une autre page, par exemple la page de confirmation
//             return $this->redirectToRoute('app_cart');
//         }
   
//         return $this->render('formules/show.html.twig', [
//             'formule' => $formule,
//             'form' => $form->createView(),
//         ]);
//     }
// }


    #[Route("/nos-formules/{slug}", name: 'formule')]
    public function show(Request $request, $slug): Response
    {
        $session = $request->getSession();
        $formule = $this->entityManager->getRepository(Formules::class)->findOneBySlug($slug);
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $products = $this->entityManager->getRepository(Product::class)->findAll();  
        
        
        if (!$formule) {
            return $this->redirectToRoute('app_formules');
        }

        $categories = $formule->getCategory();
        $products = [];

        foreach ($categories as $category) {
            $categoryProducts = $category->getProducts()->toArray();
            $products = array_merge($products, $categoryProducts);
        }

        $form = $this->createForm(PersonnalisationType::class, $formule, [
            'formule' => $formule,
            'products' => $products,
            'category' => $category,
            
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cart = $form->getData();
            // $this->entityManager->persist($cart);
            
            dd($cart);
            // Redirection vers une autre page, par exemple la page du panier
            return $this->redirectToRoute('app_cart');
        }
    
        return $this->render('formules/show.html.twig', [
            'formule' => $formule,
            'form' => $form->createView(),
        ]);
    }
}
