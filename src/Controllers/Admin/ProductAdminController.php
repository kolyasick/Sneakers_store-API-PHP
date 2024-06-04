<?php


namespace Src\Controllers\Admin;
use Src\Controllers\Controller;
use Src\Models\Products\Product;
use Src\Models\Categories\Category;
use InvalidArgumentException;
use Src\Exceptions\NotFoundException;


class ProductAdminController extends Controller {
    public function all() {
        $products = Product::findAll();
        $categories = Category::findAll();

        $this->view->renderAdmin('Admin/Product/all.php', ['products' => $products,'categories'=> $categories]);
    }

    public function add(): void {
        if (!empty($_POST)) {
            try{
                $product = Product::createProduct($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderAdmin('Admin/Product/add.php', ['error'
                => $e->getMessage(),'categories' => Category::getCategories()]);
                return;
            }
            header('Location: /admin/product/all', true, 302);
            exit();
        }
        $this->view->renderAdmin('Admin/Product/add.php', ['categories' => Category::getCategories()]);
    }

    public function view($id){
        $product = Product::getByID($id);
        $category = Product::getCategoryName($product->getCategoryId());
        if(empty($product)){
            return false;
        }
        $this->view->renderAdmin('Admin/Product/view.php', ['product' => $product, 'category' => $category]);
    }

    public function delete(int $id): void {
        $product = Product::getById($id);
        if ($product === null) {
            throw new NotFoundException();
        }
        $product->delete();
        header('Location: /admin/product/all');
    }

    public function edit(int $id): void {
        $product = Product::getById($id);
        $categories = Category::findAll();
        $category = Product::getCategoryName($product->getCategoryId());
        if ($product === null) {
            throw new NotFoundException();
        }
        if (!empty($_POST)) {
            try {
                $product->updateProduct($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('Admin/Product/edit.php', ['error'
                => $e->getMessage(), 'product' => $product]);
                return;
            }
            header('Location: /admin/product/all');
            exit();
        }
        $this->view->renderAdmin('Admin/Product/edit.php', ['product' => $product, 'categories' => $categories, 'category_once' => $category]);
}
}