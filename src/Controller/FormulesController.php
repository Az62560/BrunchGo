<?php
namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Category;
use App\Entity\Formules;
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
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager; 
        $this->requestStack = $requestStack;
    }


    #[Route('/nos-formules', name: 'app_formules')]

    public function index(): Response
    {
         $formules = $this->entityManager->getRepository(Formules::class)->findAll();
        
        return $this->render('formules/index.html.twig', [
            'formules' => $formules,
        ]);
    }


   #[Route("/nos-formules/{slug}", name: 'formule')]

    public function show(Request $request, $slug): Response
    {
      
        $formule = $this->entityManager->getRepository(Formules::class)->findOneBySlug($slug);  
        $session = $request->getSession();
            
        if (!$formule) {
           return $this->redirectToRoute('app_formules');
        }
        

        $form = $this->createForm(PersonnalisationType::class, null, [
            'formules' => [$formule]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Obtenez les données du formulaire
            $formData = $form->getData();
            $uniqId = uniqid();
            // $session = $request->getSession();
            $cart = $session->get('cart_formule');
            
            // passer les données en tableau avec les formules selectionnées et les produits associés
            $cart[$uniqId] = [$formule, $formData];

            // Ajouter les données du tableau en session
            $session->set('cart_formule', $cart);
            $session->set('cart_products', $formData);
            
            // redirection vers la page "mon-panier"
            return $this->redirectToRoute('app_cart');
            
        }

        return $this->render('formules/show.html.twig', [
            'formule' => $formule,
            'form' => $form->createView(),  
                  
        ]);
    }
}