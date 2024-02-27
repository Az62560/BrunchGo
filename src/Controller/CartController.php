<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Formules;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    private $entityManager;
    private $requestStack;
    
    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    #[Route('/mon-panier', name: 'app_cart')]

    public function index(Request $request, Cart $cart): Response
    {

        $session = $request->getSession();
        $formuleName = $session->get('cart_formule');
        $formulePrice = $session->get('cart_formule_price');
        $selectedProducts = $session->get('cart_products');
        // $formule = $session->get('')
        // dd($formule);
        return $this->render('cart/index.html.twig', [
            'formuleName' => $formuleName,
            'selectedProducts' => $selectedProducts,  
            'formulePrice' => $formulePrice,
            
            // dd($request),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_to_cart')]
 
    public function add(Request $request, Cart $cart, $id): Response
    {

    // Récupérer les produits sélectionnés à partir des cases à cocher
        $selectedProductIds = $request->request->get('selected_products');
        $selectedProducts = $this->entityManager->getRepository(Product::class)->findBy(['id' => $selectedProductIds]);
        $selectedFormule = $this->entityManager->getRepository(Formules::class)->findOneById($id);

        $session = $request->getSession();
        $session->set('cart_formule', $selectedFormule->getName());
        $session->set('cart_formule_price', $selectedFormule->getPrice());
        dd($session);

    // Ajouter l'ID de la formule au panier
        $cart->add($selectedProducts);
        $cart->add($selectedFormule);
        

        return $this->redirectToRoute('app_cart'); 
    }


    #[Route('/cart/remove', name: 'remove_my_cart')]

    public function remove(Cart $cart): Response
    {   
        $cart->remove();
        return $this->redirectToRoute('app_formules');
    }

}
