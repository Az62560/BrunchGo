<?php

namespace App\Classe;

use App\Entity\Formules;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->session->remove('cart_formule');
    }
    // public function delete($id){
    //     $cart = $this->session->get('cart', []);
    //     unset($cart[$id]);
    //     return $this->session->set('cart', $cart);
    // }
    
    



    // public function getFormulePerso(Cart $cart) 
    // {
    //     $formulePerso = [];

    //     if ($this->get()) {
    //         foreach ($this->get() as $id => $products) {
    //             $selectedProductIds = $cart->session->get('selected_products');
    //             $products = $this->entityManager->getRepository(Product::class)->find(['id' => $selectedProductIds]);
    //             $formule = $this->entityManager->getRepository(Formules::class)->find($id);

    //             $formulePerso[] = [
    //                 'formule' => $formule,
    //                 'products' => $products,
    //                 'selectedProductIds' => $selectedProductIds,
    //             ];
    //         }
    //     }
    //     return $formulePerso;
    // }
}