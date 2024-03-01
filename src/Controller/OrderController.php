<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\TimeSlots;
use App\Entity\WorkingDay;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {

        $this->entityManager = $entityManager;

    }

    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()) 
        {
            return $this->redirectToRoute('app_account_address_add');
        } 
        
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
            'workingDays' => $this->entityManager->getRepository(WorkingDay::class)->findByAvailable(1),
            'timeSlots' => $this->entityManager->getRepository(TimeSlots::class)->findByIsFree(1),
    
            // 'timeSlots' => $this->getHours(),
        ]);
        $session = $request->getSession();
        $formules = $session->get('cart_formule');
        $selectedProducts = $session->get('cart_products');

        return $this->render('order/index.html.twig', [
           
            'form' => $form->createView(),
            'cart' => $cart->get(),
            'formules' => $formules,
            'selectedProducts' => $selectedProducts,
            // 'timeSlots' => $timeSlots,
        ]);
        
    }

    #[Route('/commande/recapitulatif', name: 'app_order_recap')]
    public function add(): Response
    {
        return $this->render('order/index.html.twig');
    }
}
