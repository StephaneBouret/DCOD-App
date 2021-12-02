<?php

namespace App\Classe;

use App\Entity\Plan;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart 
{
    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function add($id)
    {
        $session = $this->requestStack->getSession();
        
        return $session->set('cart', [
            'id' => $id,
            'quantity' => 1
        ]);
    }

    public function get()
    {
        $session = $this->requestStack->getSession();
        return $session->get('cart');
    }

    public function remove()
    {
        $session = $this->requestStack->getSession();
        return $session->remove('cart');
    }

    public function removeUser()
    {
        $session = $this->requestStack->getSession();
        return $session->remove('user');
    }

    public function delete($id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', [$id]);
        
        return $session->remove('cart', $cart);
    }

    public function getfull()
    {
        $session = $this->requestStack->getSession();
        $cartComplete = [];

        if($this->get()) {
            foreach ($this->get() as $id => $value) {
                $product_object = $this->entityManager->getRepository(Plan::class)->findOneById($value);

                if ($product_object == null) {
                    return $session->set('cart', []);
                    continue;
                }
                
                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => 1
                ];
    
                return $cartComplete;
            }               
        }

    }
}