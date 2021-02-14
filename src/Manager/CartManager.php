<?php

namespace App\Manager;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class CartManager 
{
    private $session;

    private $em;

    public function __construct(SessionInterface $session,
                                EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function get(){
        return $this->session->get('cart');
    }

    public function getFull(){
        $cartDetails = [];

        if($this->get()){
            foreach($this->get() as $id => $quantity) {
                
                if(!$product = $this->em->getRepository(Product::class)->findOneById($id)){
                    $this->delete($id);
                    continue;
                }

                $cartDetails[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartDetails;
    }

    public function add($id) {
        $cart = $this->session->get('cart',[]);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function decrease($id) {
        $cart = $this->session->get('cart',[]);

        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }

    public function delete($id) {
        $cart = $this->session->get('cart',[]);

        unset($cart[$id]);

        $this->session->set('cart', $cart);
    }

    public function remove(){
        return $this->session->remove('cart');
    }
}