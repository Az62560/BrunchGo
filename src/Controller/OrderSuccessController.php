<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderSuccessController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    } 

    #[Route('/commande/merci/{stripe_session_id}', name: 'app_order_success')]
    public function success(Cart $cart, $stripe_session_id): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripe_session_id);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($order->getState() == 0){
            //Vider la session 'cart'.
            $cart->remove();

            //Modifier le statut state de la commande de 0 à 1.
            $order->setState(1);
            $this->entityManager->flush();

            //envoi d'un mail pour lui confirmer sa commande
            $mail = new Mail();
            $content = 'Bonjour'.$order->getUser()->getFirstname().'<br>Merci pour votre commande.';
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Confirmation de commande sur le site Bruncho Go', $content);
        }

        return $this->render('order_success/index.html.twig', [
            'stripe_session_id' => $stripe_session_id,
            'order' => $order,
         ]);
    }
}
