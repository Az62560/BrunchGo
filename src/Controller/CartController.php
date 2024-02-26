<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Formules;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-panier', name: 'app_cart')]

    public function index(Request $request): Response
    {

// $formulePrice = $this->entityManager->getRepository(Formules::class)->findOneById($id);
        $session = $request->getSession();
        $formule = $session->get('cart_formule');
        $selectedProducts = $session->get('cart_products');
        // $ormule = $session->get('')
        // dd($formule);
        // dd($selectedProducts);
        return $this->render('cart/index.html.twig', [
            // 'formulePrice' => $formulePrice,
            'formule' => $formule,
            'selectedProducts' => $selectedProducts,    
            // dd($request),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_to_cart')]
 
    public function add(Cart $cart, $id): Response
    {
    // Récupérer l'ID de la formule
        $formuleId = $id;
    // Récupérer les produits sélectionnés à partir des cases à cocher
        $selectedProductIds = $this->entityManager->getRepository('selected_products');
        $selectedProducts = $this->entityManager->getRepository(Product::class)->findBy(['id' => $selectedProductIds]);
        $selectedFormule = $this->entityManager->getRepository(Formules::class)->findAll($id);

    // Ajouter l'ID de la formule au panier
        $cart->add($formuleId);
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
