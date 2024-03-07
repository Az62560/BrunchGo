<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Formules;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'app_stripe_create_session')]
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference, $selected_formules): Response
    {

        $stripe = new StripeClient('sk_test_51OqXJJGKBR4VtUNOHET0EXnbApLTeWQPdEz3dy1PSBM3qkGeuXVkZ4y6tXgZ1xk3dYRZFwE3IA90L3EmxOIegeR800yEJPVYLN');

        // Création des lignes de commande pour Stripe
        $lineItems = [];
        $total = 0;

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }

        foreach ($selected_formules as $formuleArray) {
            // $formule = $entityManager->getRepository(Formules::class)->findOneByName($formuleArray[0]->getName());
            $formule = $formuleArray[0]; // Récupérer l'objet Formule
            $formulePrice = $formule->getPrice(); // Obtenir le prix de la formule
            $total += $formulePrice; // Ajouter le prix de la formule au total
            
            // Créer l'élément pour la session Stripe
            $lineItems[] = [
                'price_data' => [
                    'unit_amount' => $formulePrice, // Montant en centimes
                    'currency' => 'eur', // Devise de la transaction en euro
                    'product_data' => [
                        'name' => $formule->getName(),
                    ],
                ],
                'quantity' => 1, // Quantité
            ];
        }
        // dd($lineItems);
        // Création de la session Stripe
        $session = $stripe->checkout->sessions->create([
            'success_url' => 'http://178.33.104.60:8001',
            'cancel_url' => 'http://178.33.104.60:8001',
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
        ]);

        return $this->render('order/add.html.twig', [
            'cart' => $cart->get(),
            'order' => $order,
            'reference' => $order->getReference(),
            'selected_formules' => $selected_formules,
            'stripe_session_id' => $session->id, // Passer l'identifiant de la session Stripe à la vue
        ]);
        $order->setStripeSessionId($session->id);
        // $entityManager->flush();

        $response = new JsonResponse(['id' => $session->id]);
        return $response;
 


        return $this->render('stripe/index.html.twig');
    }
}
