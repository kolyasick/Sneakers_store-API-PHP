<?php


namespace Src\Controllers\Admin;
use Src\Controllers\Controller;
use Src\Models\Categories\Category;
use Src\Models\Order\Order;
use InvalidArgumentException;
use Src\Exceptions\NotFoundException;

class OrderAdminController extends Controller {
    public function all() {
        $orders = Order::findAll();
        $this->view->renderAdmin('Admin/Order/all.php', ['orders' => $orders]);
    }

    public function add(): void {
        if (!empty($_POST)) {
            try{
                $category = Category::createCategory($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderAdmin('Admin/Category/add.php', ['error'
                => $e->getMessage()]);
                return;
            }
            header('Location: /admin/category/all', true, 302);
            exit();
        }
        $this->view->renderAdmin('Admin/Category/add.php', ['categories' => Category::getCategories()]);
    }

    public function view($id){
        $order = Order::getByID($id);
        if(empty($order)){
            return false;
        }
        $this->view->renderAdmin('Admin/Order/view.php', ['order' => $order]);
    }

    public function edit(int $id): void {
            $order = Order::getById($id);
            $orders = Order::findAll();
            if ($order === null) {
                throw new NotFoundException();
            }
            if (!empty($_POST)) {
                try {
                    $order->updateOrder($_POST);
                } catch (InvalidArgumentException $e) {
                    $this->view->renderHtml('Admin/Order/all.php', ['error'
                    => $e->getMessage(), 'order' => $order]);
                    return;
                }
                header('Location: /admin/order/all');
                exit();
            }
            $this->view->renderAdmin('Admin/Order/all.php', ['orders' => $orders, 'order' => $order]);
    }

    public function delete(int $id): void {
        $order = Order::getById($id);
        if ($order === null) {
            throw new NotFoundException();
        }
        $order->delete();

        header('Location: /admin/order/all');
    }
}