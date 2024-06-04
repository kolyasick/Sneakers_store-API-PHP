<?php

namespace Src\Controllers;

use Src\Models\Products\Product;
use Src\Exceptions\NotFoundException;
use Src\Models\Cart;
use Src\Models\Order\Order;
use Src\Models\Order\OrderProduct;
use Src\Exceptions\InvalidArgumentException;

class CartController extends Controller {
    public function add($id){
        $product = Product::getById($id);
        if(empty($product)){
            return false;
        }
        $cart = new Cart();
        $cart->addToCart($product);
    } 

    public function addInCart($id){
        $product = Product::getById($id);
        if(empty($product)){
            return false;
        }
        $cart = new Cart();
        $cart->addToCart($product);
        header('Location: /product/all');
    } 

    public function delItem($id){
        $cart = new Cart();
        $product = Product::getById($id);
        if(empty($product)){
            return false;
        }
        $cart = new Cart();
        $cart->addToCart($product, -1);
        if ($_SESSION['cart'][$id]['qty'] < 1) {
            unset($_SESSION['cart'][$id]);
        } 
        header('Location: /cart');
    }

    public function deleteInCart($id) {
        $product = Product::getById($id);
        $cart = new Cart();
        $cart->addToCart($product, -$_SESSION['cart'][$id]['qty']);
        unset($_SESSION['cart'][$id]);
        header('Location: /cart');
    }

    public function deleteInProducts($id) {
        unset($_SESSION['cart'][$id]);
        header('Location: /');
    }

    public function clear() {
        unset($_SESSION['cart']);
        unset($_SESSION['cart.sum']);
        header('Location: /cart');
    }
    public function view() {
        
        if(isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            $this->view->renderHtml('Cart/view.php', ['cart' => $_SESSION['cart'], 'cart.sum' => $_SESSION['cart.sum']]);
        } else {
            $this->view->renderHtml('Cart/view.php');
        }
       
    }
    public function order() {

        if(isset($_SESSION['cart'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($_SESSION['cart']);
        } else {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode([]);
        }
    }
    public function orderPost() {
        
        if(!empty($_POST)) {
            try {
                $order= Order::saveOrder($_POST);
                if(OrderProduct::saveOrderProducts($_POST['cartItems'], $order->getId())) {
                    http_response_code(200);
                    echo json_encode('Order successful');

                    return;
                }
            } catch (InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(['Error'=>'Something went wrong']);
            }
            
        } http_response_code(400);
            echo json_encode(['Error'=>'No data']);
    } 

}