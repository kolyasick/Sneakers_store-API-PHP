<?php

namespace Src\Controllers;

use InvalidArgumentException;
use Src\Models\Users\UserAuthService;
use Src\Views\View;
use Src\Models\Products\Product;
use Src\Models\Categories\Category;
use Src\Models\Users\User;
use Src\Exceptions\NotFoundException;
use Src\Exceptions\UnauthorizedException;
use UnderflowException;

class ProductController extends Controller {

    public function view(int $id) {
        $products = Product::getByID($id);

        if ($products) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($products);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No products found"));
        }
    }   

    public function all() {
        $products = Product::findAll();

        if ($products) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($products);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No products found"));
        }

    }

}