<?php


namespace Src\Controllers\Admin;
use Src\Controllers\Controller;
use Src\Models\Categories\Category;
use InvalidArgumentException;
use Src\Exceptions\NotFoundException;

class CategoryAdminController extends Controller {
    public function all() {
        $categories = Category::getCategories();
        $subcategories = Category::getSubcategories();
        $this->view->renderAdmin('Admin/Category/all.php', ['categories' => $categories, 'subcategories' => $subcategories]);
    }

    public function add(): void {
        if (!empty($_POST)) {
            try{
                $category = Category::createCategory($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderAdmin('Admin/Category/add.php', ['error'
                => $e->getMessage(), 'categories' => Category::getCategories()]);
                return;
            }
            header('Location: /admin/category/all', true, 302);
            exit();
        }
        $this->view->renderAdmin('Admin/Category/add.php', ['categories' => Category::getCategories()]);
    }

    public function view($id){
        $category = Category::getByID($id);
        if(empty($category)){
            return false;
        }
        $this->view->renderAdmin('Admin/Category/view.php', ['category' => $category]);
    }

    public function edit(int $id): void {
            $category = Category::getById($id);
            if ($category === null) {
                throw new NotFoundException();
            }
            if (!empty($_POST)) {
                try {
                    $category->updateCategory($_POST);
                } catch (InvalidArgumentException $e) {
                    $this->view->renderHtml('Admin/Category/edit.php', ['error'
                    => $e->getMessage(), 'category' => $category]);
                    return;
                }
                header('Location: /admin/category/all');
                exit();
            }
            $this->view->renderAdmin('Admin/Category/edit.php', ['category' => $category]);
    }

    public function delete(int $id): void {
        $category = Category::getById($id);
        if ($category === null) {
            throw new NotFoundException();
        }
        $category->delete();

        header('Location: /admin/category/all');
    }
}