<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Formules;
use App\Entity\Order;
use App\Entity\TimeSlots;
use App\Entity\WorkingDay;
use App\Form\OrderType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('app_account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
            'workingDays' => $this->entityManager->getRepository(WorkingDay::class)->findByAvailable(1),
            'timeSlots' => $this->entityManager->getRepository(TimeSlots::class)->findByIsFree(1),
        ]);
        
        $session = $request->getSession();
        $selected_formule = $session->get('cart_formule');
        $selectedProducts = $session->get('cart_products');

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->get(),
            'selected_formule' => $selected_formule,
            'selectedProducts' => $selectedProducts,
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'app_order_recap')]
    public function add(Cart $cart, Request $request): Response
{
    $order = new Order();
    $workingDays = $this->entityManager->getRepository(WorkingDay::class)->findByAvailable(1);
    $timeSlots = $this->entityManager->getRepository(TimeSlots::class)->findByIsFree(1);
  
    $session = $request->getSession();
    
    $selected_formules = $session->get('cart_formule');
    $selectedProducts = $session->get('cart_products');
    
    $form = $this->createForm(OrderType::class, $order, [
        'user' => $this->getUser(),
        'workingDays' => $workingDays,
        'timeSlots' => $timeSlots,
        'selected_formule' => $selected_formules,
    ]);
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $date = new DateTimeImmutable();
        $formData = $form->getData();
        
        // Récupérer les données du formulaire
        $deliveryAddress = $formData->getDeliveryAddress();
        $deliveryDay = $formData->getDeliveryDay();
        $deliveryHour = $formData->getDeliveryHour();
        $total = 0; // Initialiser le total à 0
        
        // Initialisation de Stripe
       
        $stripe = new StripeClient('sk_test_51OqXJJGKBR4VtUNOHET0EXnbApLTeWQPdEz3dy1PSBM3qkGeuXVkZ4y6tXgZ1xk3dYRZFwE3IA90L3EmxOIegeR800yEJPVYLN');

        // Création des lignes de commande pour Stripe
        $lineItems = [];
        $total = 0;

        foreach ($selected_formules as $formuleArray) {
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

        // Attribution des valeurs à l'objet Order
        $order->setDeliveryDay($deliveryDay);
        $order->setDeliveryHour($deliveryHour);
        $order->setDeliveryAddress($deliveryAddress);
        $order->setTotal($total);
        $order->setReference($date->format('dmY') . '-' . uniqid());
        $order->setUser($this->getUser());
        $order->setCreatedAt($date);
        $order->setState(0);
        $order->setSelectedFormule($selected_formules);

        // Persist the order entity
        $this->entityManager->persist($order);

        $session = $stripe->checkout->sessions->create([
            'success_url' => 'http://178.33.104.60:8001',
            // 'cancel_url' => 'URL_de_annulation',
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            
            'mode' => 'payment',
        ]);
        // dd($lineItems);
        // $this->entityManager->flush();
        
        return $this->render('order/add.html.twig', [
            'cart' => $cart->get(),
            'order' => $order,
            'reference' => $order->getReference(),
            'selected_formules' => $selected_formules,
            'stripe_session_id' => $session->id, // Passer l'identifiant de la session Stripe à la vue
        ]);
    }
    
    // Redirection après la soumission réussie
    return $this->redirectToRoute('app_cart');
}
}
