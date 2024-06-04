<?php

namespace Src\Controllers;
use Src\Views\View;
use Src\Models\Products\Product;
use Src\Models\Reviews\Review;
use Src\Models\Categories\Category;
use Src\Models\Users\User;
use Src\Models\Order\Order;
use Src\Exceptions\InvalidArgumentException;

class MainController extends Controller { 
    public function main() {
        $products = Product::getSaleProducts();
        $categories = Category::getCategories();
        $reviews = Review::findAll();
        $orders = Order::findAll();
        if ($products && $categories && $reviews === null) {
            return false; 
        } 
        $this->view->renderHtml('Main/main.php', ['products' => $products, 'categories' => $categories, 'reviews' => $reviews, 'orders' => $orders]);
    }

    public function add(): void {
        if (!empty($_POST)) {
            try{
                $review = Review::createReview($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('Reviews/add.php', ['error'
                => $e->getMessage()]);
                return;
            }
            header('Location: /#reviews', true, 302);
            exit();
        } $this->view->renderHtml('Reviews/add.php', ['review' => Review::findAll()]);
        
    }

}