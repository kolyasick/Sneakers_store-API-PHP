<?php 

namespace Src\Models;
use Src\Models\Products;
use Src\Models\Products\Product;

class Cart {
    public function addToCart($product, $qty = 1){
        $product->setOffer();

        if(isset($_SESSION['cart'][$product->getId()])) {
            $_SESSION['cart'][$product->getId()]['qty'] += $qty;
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($_SESSION['cart']);
        } else {
            $_SESSION['cart'][$product->getId()] = [
                'title' => $product->getTitle(),
                'price' => $product->getPrice(),
                'qty' => $qty,
                'img' => $product->getImg(),
                'id' => $product->getId()
            ];
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($_SESSION['cart']);
        }
        $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * $product->getPrice() : $qty * $product->getPrice();


    }

    public function delete(int $id): void {
        unset($_SESSION['cart'][$id]);
    }

}