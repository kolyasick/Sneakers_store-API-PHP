<?php

namespace Src\Controllers;

use InvalidArgumentException;
use Src\Models\Users\UserAuthService;
use Src\Views\View;
use Src\Models\Categories\Category;
use Src\Models\Users\User;
use Src\Exceptions\NotFoundException;
use Src\Exceptions\UnauthorizedException;
use Src\Models\Products\Product;
use UnderflowException;

class CategoriesController extends Controller {

    public function view(int $id) {
        $products = Product::getProductsByCategoryId($id);

        if (!$products) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'No products found']);
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($products);
    }

    public function all() {
        $categories = Category::getCategories();
        $subcategories = Category::getSubcategories();

        if (!$categories) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'No categories found']);
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($categories);
    }

    public function search() {
        if (!empty($_GET)){
            if (!isset($_GET['q'])) {
                http_response_code(400);
                header('Content-Type: application/json');
                echo $_GET['q'];
            }
            $searchProducts = Product::search($_GET['q']);
            if (!$searchProducts) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($searchProducts);
            } else {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($searchProducts);
            }


        }
       

    }

}