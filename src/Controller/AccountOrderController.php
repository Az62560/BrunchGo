<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AccountOrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/mes-commandes', name: 'app_account_order')]
    public function index($session): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());
        $selected_formule = $session->get('selected_formule');
        return $this->render('account/order.html.twig', [
            'orders' => $orders,
         
           'formuleData' => $orders->getSelectedFormule(),

        ]);
    }

    #[Route('/compte/mes-commandes/{reference}', name: 'app_account_order_show')]

    public function show(Cart $cart, Request $request, $reference): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);
        
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_order');
        }
    
        // Récupérez les données de formule et de produit associées à la commande
        $formuleData = $order->getSelectedFormule();
    
        // Passez les données de formule à la vue Twig
        return $this->render('account/order_show.html.twig', [
            'cart' => $cart->get(),
            'order' => $order,
            'reference' => $order->getReference(),
           
        ]);
    }
    
}