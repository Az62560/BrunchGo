<?php
namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Formules;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class StripeController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/create-session/{reference}/{selected_formule}', name: 'app_stripe_create_session')]
    public function stripeCheckout(Cart $cart, $reference, Request $request, $selected_formule): RedirectResponse
    { 
        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);

        $session = $request->getSession();
        $cartFormules = $session->get('cart_formule');
    
        // Assurez-vous que les données des formules sont disponibles
        if (!$cartFormules) {
            // Rediriger si les données ne sont pas disponibles
            return $this->redirectToRoute('app_cart');
        }
    
        // Initialisez un tableau pour stocker les noms des formules
        

        Stripe::setApiKey('sk_test_51OqXJJGKBR4VtUNOHET0EXnbApLTeWQPdEz3dy1PSBM3qkGeuXVkZ4y6tXgZ1xk3dYRZFwE3IA90L3EmxOIegeR800yEJPVYLN');

        // Création des lignes de commande pour Stripe
        $lineItems = [];
        $formuleNames = [];
        $formulePrice = [];
    
        // Parcourez les données des formules pour extraire les noms
        foreach ($cartFormules as $formuleData) {
            if (!empty($formuleData[0])) {
                $formule = $formuleData[0];
                $formuleNames[] = $formule->getName();
                $formulePrice = $formule->getPrice(); // Prix de la formule actuelle
                // Créer l'élément pour la session Stripe pour cette formule spécifique
                $lineItems[] = [
                    'price_data' => [
                        'unit_amount' => $formulePrice,
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $formule->getName(),                          
                        ],
                    ],
                    'quantity' => 1,
                ];  
            }
        }
        // dd($lineItems);
        // Création de la session Stripe
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'success_url' => 'http://178.33.104.60:8001/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://178.33.104.60:8001/commande/erreur/{CHECKOUT_SESSION_ID}',
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
        ]);
        

        $order->setStripeSessionId($checkout_session->id);
        $this->entityManager->flush();
        // Faites quelque chose avec $checkout si nécessaire
        return new RedirectResponse($checkout_session->url, 303);
        // Redirection vers une autre page ou traitement supplémentaire
        // return $this->redirectToRoute('app_formules');

        
    }
}
