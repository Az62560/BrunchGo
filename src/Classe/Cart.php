<?php

namespace App\Classe;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $session;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $currentRequest = $requestStack->getCurrentRequest();

        if ($currentRequest) {
            $this->session = $currentRequest->getSession();
            $this->entityManager = $entityManager;
        }
    }

    public function add($id)
    {
        $cart = $this->session->get('cart', []);
        
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart', []);
    }

    public function remove()
    {
    
        return $this->session->remove('cart');
    }

    // public function getFull() 
    // {
    //     $cartComplete = [];

    //     if ($this->get()) {
    //         foreach ($this->get() as $id => $quantity) {
    //             $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

    //             if (!$product_object) {
    //                $this->delete($id);
    //                continue; 
    //             }

    //             $cartComplete[] = [
    //                 'product' => $product_object,
    //                 'quantity' => $quantity
    //             ];
    //         }
    //     }
    //     return $cartComplete;
    // }
}