<?php

namespace Src\Controllers;
use Src\Views\View;
use Src\Services\Db;
use Src\Models\Users\UserAuthService;

class Controller {
    protected $view;
    protected $user;
    protected $layout = 'default'; 

    public function __construct() {
        $this->user = UserAuthService::getUserByToken();
        $this->view = new View($this->layout);
        $this->view->setVar('user', $this->user);
    }
}