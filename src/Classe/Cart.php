<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {
        
    }
    public function add($product){
        
        //Appeler la session de symfony
        $cart = $this->requestStack->getSession()->get('cart');

        if(isset($cart[$product->getId()])){
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => $cart[$product->getId()]['quantity'] + 1
            ];
        }else{

            $cart[$product->getId()] = [
            'object' => $product,
            'quantity' => 1
        ];

        }

        

        $this->requestStack->getSession()->set('cart', $cart);

    }

    public function fullQuantity(){
        $cart = $this->requestStack->getSession()->get('cart');

        $quantity = 0;

        if(!isset($cart)){
            return $quantity;
        }
       
        foreach ($cart as $product) {
            $quantity = $quantity + $product["quantity"];
        }

        return $quantity;
    }

    public function getTotalWt(){
         $cart = $this->requestStack->getSession()->get('cart');

        $price = 0;
        // dd($cart);

        if(!isset($cart)){
            return $price;
        }
        foreach ($cart as $product) {
            $price = $price + ($product["object"]->getPriceWt() * $product["quantity"]);
        }

        return $price;
    }


    public function remove(){
        $this->requestStack->getSession()->remove('cart');
    }

    public function decrease($id){
        $cart = $this->requestStack->getSession()->get('cart');

        if($cart[$id]['quantity'] > 1){
            $cart[$id]['quantity'] = $cart[$id]['quantity'] -1;
        }else{
            unset( $cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function getCart(){
        return $this->requestStack->getSession()->get('cart');
    }

}