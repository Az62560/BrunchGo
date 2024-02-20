<?php

namespace App\Classe;

use App\Entity\Formules;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
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
        return $this->session->get('cart');
    }

    public function getFull() 
    {
        $cartComplete = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $selected_product = $this->entityManager->getRepository(Product::class)->findManyById($id);
                $selected_formule = $this->entityManager->getRepository(Formules::class)->findManyById($id);
                

                $cartComplete[] = [
                    'product' => $selected_product,
                    'formule' => $selected_formule,
                ];
            }
        }
        return $cartComplete;
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }
}