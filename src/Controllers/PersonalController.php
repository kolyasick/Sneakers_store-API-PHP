<?php

namespace Src\Controllers;

use Src\Models\Categories\Category;
use Src\Models\Order\Order;
use Src\Models\Products\Product;
use Src\Models\Users\User;
use Src\Exceptions\InvalidArgumentException;
use Src\Exceptions\NotFoundException;
use Src\Models\Order\OrderProduct;

class PersonalController extends Controller {

   public function profile() {
      $this->view->renderHtml('Personal/profile.php');
   }

   public function edit(int $id): void {
        $user = User::getById($id);
        if (!empty($_POST) && !empty($_POST['password']) && !empty($_POST['new_password'])) {
            try {
                $user->updateUserPass($_POST);
            } catch (InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode('Password');
            } http_response_code(200);
            echo json_encode('Password succesfully changed');

        } else if (!empty($_POST) && !empty($_POST['nickname']) && !empty($_POST['email'])) {
            try {
                $user->updateUser($_POST);
            } catch (InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode('Error');
            } http_response_code(200);

        }  
        
    }
    public function orders(int $id) {
        $user = User::getById($id);
        $orders = Order::getOrdersByEmail($user->getEmail());
        if(empty($orders)){
            $this->view->renderHtml('Personal/orders.php', ['user' => $user, 'orders' => $orders]);
                return;
        }
        $this->view->renderHtml('Personal/orders.php', ['user' => $user, 'orders' => $orders]);
    }

    public function orderView(int $id) {
        $orders = OrderProduct::getOrdersByCategoryId($id);

        $products = Product::getOrdersProducts($orders);   

        if(empty($orders) || empty($products)) {
            return false;
        }
        $this->view->renderHtml('Personal/orderView.php', [ 'orders' => $orders, 'products' => $products]);
    }

}