<?php


namespace Src\Controllers\Admin;
use Src\Controllers\Controller;


class MainAdminController extends Controller {
    public function index() {
        $this->view->renderAdmin('Admin/Main/default.php');
    }
}