<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Formules;
use App\Entity\Order;
use Stripe\Stripe;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'app_stripe_create_session')]
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {

        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://178.33.104.60:8001';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }

        foreach ($order->getSelectedFormule()->getValues() as $formules) {
            $formules = $entityManager->getRepository(Formules::class)->findOneByName($formules);
            $products_for_stripe[] = [
                'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $formules->getPrice(),
                'product_data' => [
                    'name' => $formules->getName(),
                ],
            ],
            'quantity' => $formules->getQuantity(),
        ]; 
        }

        Stripe::setApiKey('sk_test_51OqXJJGKBR4VtUNOHET0EXnbApLTeWQPdEz3dy1PSBM3qkGeuXVkZ4y6tXgZ1xk3dYRZFwE3IA90L3EmxOIegeR800yEJPVYLN');
  
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
