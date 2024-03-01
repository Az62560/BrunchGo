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
use Symfony\Component\Routing\Annotation;

class CartController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-panier', name: 'app_cart')]

    public function index(Request $request, Cart $cart): Response
    {
        $session = $request->getSession();
        $formules = $session->get('cart_formule');
        $selectedProducts = $session->get('cart_products');  
        // dd($session);
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'formules' => $formules,
            'selectedProducts' => $selectedProducts,    
            
        ]);
    }


    
    #[Route('/cart/add/{id}', name: 'add_to_cart')]
 
    public function add(Request $request, Cart $cart, $id): Response
    {

    // Récupérer les produits sélectionnés à partir des cases à cocher
        $selectedProductIds = $request->request->get('selected_products');
        $selectedProducts = $this->entityManager->getRepository(Product::class)->findBy(['id' => $selectedProductIds]);
        $selectedFormule = $this->entityManager->getRepository(Formules::class)->findOneById($id);


    // Ajouter l'ID de la formule au panier
    $cart->add($selectedFormule);
    foreach ($selectedProducts as $product) {
        $cart->add($product);
        
    }
        return $this->redirectToRoute('app_cart'); 
    }


    #[Route('/cart/remove', name: 'remove_my_cart')]

    public function remove(Cart $cart): Response
    {   
        $cart->remove();
        return $this->redirectToRoute('app_formules');
    }
   
    #[Route('/cart/delete/{uniqId}', name: 'delete')]
    public function delete(Cart $cart, $uniqId)
    {
        // Supprimer la formule spécifique du panier
        $cart->delete($uniqId);
        // Rediriger vers la page du panier
        return $this->redirectToRoute('app_cart');
    }






    // #[Route('/cart/delete/formule/{id}', name: 'delete_formule')]
    // public function deleteFormule($id): Response
    // {
    //     $cart = $this->session->get('cart', []);

    //     // Recherchez la formule dans le tableau cart_formule
    //     $cartFormules = $cart['cart_formule'] ?? [];
    //     foreach ($cartFormules as $key => $formuleArray) {
    //         // Si l'ID de la formule correspond à celui que vous souhaitez supprimer
    //         if ($formuleArray[0]->getId() == $id) {
    //             // Supprimez la formule du tableau cart_formule
    //             unset($cart['cart_formule'][$key]);
    //             // Sortez de la boucle, car la formule a été trouvée et supprimée
    //             break;
    //         }
    //     }

    //     // Mettez à jour le panier dans la session
    //     $this->session->set('cart', $cart);

    //     return $this->redirectToRoute('app_cart');
    // }
   
}

