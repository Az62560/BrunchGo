<?php

namespace App\Classe;

use App\Entity\Category;
use App\Entity\Formules;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    //création de la session grace à 'ResquestStack'
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

    //fonction d'ajout au panier
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

    //fonction de visualisation global du panier
    public function get()
    {
        return $this->session->get('cart', []);
    }

    //fonction de récupération de toutes les infos de la formule
    public function getFull() 
    {
        $cartComplete = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $selected_product = $this->entityManager->getRepository(Product::class)->findOneById($id);
                $selected_formule = $selected_product->getFormule();
                $selected_category = $selected_formule->getCategory();
                

                $cartComplete[] = [
                    'product' => $selected_product,
                    'formule' => $selected_formule,
                    'category' => $selected_category,
                    'quantity' => $quantity,
                ];
            }
        }
        return $cartComplete;
    }
    
    //fonction vidage du panier
    public function remove()
    {
        return $this->session->remove('cart');
    }

    //fonction supprimer la formule
    public function delete($id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);
       
        return $this->session->set('cart', $cart);
    }
}