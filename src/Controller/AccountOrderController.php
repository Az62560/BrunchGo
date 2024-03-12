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
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());
    
        return $this->render('account/order.html.twig', [
            'orders' => $orders,


        ]);
    }

    #[Route('/compte/mes-commandes/{reference}', name: 'app_account_order_show')]

    public function show($reference): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);
        
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_order');
        }
    
    $formuleData = [];
    $productsData = [];

    $orderDetails = $order->getOrderDetails();

    foreach ($orderDetails as $orderDetail) {
        $formuleData[] = $orderDetail->getFormule();
        $productsData[] = $orderDetail->getProducts();
    }
        
    
        // Passez les données de formule à la vue Twig
        return $this->render('account/order_show.html.twig', [
            'order' => $order,
            'reference' => $order->getReference(),
            'orderDetails' => $orderDetails,
            'formuleData' => $formuleData,
            'productsData' => $productsData,
        ]);
    }
    
}