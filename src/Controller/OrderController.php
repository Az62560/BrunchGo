<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\TimeSlots;
use App\Entity\WorkingDay;
use App\Form\OrderType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
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
        //   dd($selectedProducts),
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'app_order_recap')]
    public function add(Cart $cart, Request $request): Response
    {
        $order = new Order();
        $workingDays = $this->entityManager->getRepository(WorkingDay::class)->findByAvailable(1);
        $timeSlots = $this->entityManager->getRepository(TimeSlots::class)->findByIsFree(1);
      
        $session = $request->getSession();
        $selected_formule = $session->get('cart_formule');
        // dd($selected_formule);
        $selectedProducts = $session->get('cart_products');
      

        $form = $this->createForm(OrderType::class, $order, [
            'user' => $this->getUser(),
            'workingDays' => $workingDays,
            'timeSlots' => $timeSlots,
            'selected_formule' => $selected_formule,
           
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTimeImmutable();
            $formData = $form->getData();
            //  dd($selected_formule);
            // Récupérer les données du formulaire
            $deliveryAddress = $formData->getDeliveryAddress();
            $deliveryDay = $formData->getDeliveryDay();
            $deliveryHour = $formData->getDeliveryHour();
            
            // Attribution des valeurs à l'objet Order
            $order->setDeliveryDay($deliveryDay);
            $order->setDeliveryHour($deliveryHour);
            $order->setDeliveryAddress($deliveryAddress);
            $order->setReference($date->format('dmY') . '-' . uniqid());
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setState(0);
            
            $order->setSelectedFormule($selected_formule);

            // Persist the order entity
            $this->entityManager->persist($order);
            // dd($order);
            // $this->entityManager->flush();

        // Stripe::setApiKey('sk_test_51OqXJJGKBR4VtUNOHET0EXnbApLTeWQPdEz3dy1PSBM3qkGeuXVkZ4y6tXgZ1xk3dYRZFwE3IA90L3EmxOIegeR800yEJPVYLN');
  
        // $YOUR_DOMAIN = 'http://178.33.104.60:8001';

        // $checkout_session = Session::create([
        //     'payment_method_types' => ['card'],
        //     'line_items' => [
        //         'price_data' => [
        //             'currency' => 'eur',
        //             'unit_amount' => 2000,
        //             'product_data' => [
        //                 'name' => 'test',
        //                 'images' => [$YOUR_DOMAIN],
        //             ],
        //         ],
        //         'quantity' => 1,
        //     ], 
        //     'mode' => 'payment',
        //     'success_url' => $YOUR_DOMAIN . '/success.html',
        //     'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
            
        // ]);
            

        //     dump($checkout_session->id);
        //     dd($checkout_session);
       
            return $this->render('order/add.html.twig', [
            'cart' => $cart->get(),
            'order' => $order,
            // 'form' => $form->createView(),
            'reference' => $order->getReference(),    
            'selected_formule' => $selected_formule,        
        ]);

            
        }
        // Redirection après la soumission réussie
        return $this->redirectToRoute('app_cart'); // Remplacez par la route de votre choix
        
    }
}
