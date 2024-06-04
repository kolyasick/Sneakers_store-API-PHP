<?php
return [
    '~^articles/all$~' => [\Src\Controllers\ArticlesController::class, 'all'],
    '~^articles/(\d+)/edit$~' => [\Src\Controllers\ArticlesController::class, 'edit'],
    '~^articles/add$~' => [\Src\Controllers\ArticlesController::class, 'add'],
    '~^articles/(\d+)/delete$~' => [\Src\Controllers\ArticlesController::class, 'delete'],
    '~^articles/(\d+)$~' => [\Src\Controllers\ArticlesController::class, 'view'],

    '~^categories/(\d+)$~' => [\Src\Controllers\CategoriesController::class, 'view'],
    '~^categories/all$~' => [\Src\Controllers\CategoriesController::class, 'all'],

    '~^users/register$~' => [\Src\Controllers\UsersController::class, 'signUp'],
    '~^users/login$~' => [\Src\Controllers\UsersController::class, 'login'],
    '~^users/logout$~' => [\Src\Controllers\UsersController::class, 'logout'],
    '~^users/info$~' => [\Src\Controllers\UsersController::class, 'getUser'],


    '~^echo/(.*)$~' => [\Src\Controllers\MainController::class, 'echoNew'],
    '~^hello/(.*)$~' => [\Src\Controllers\MainController::class, 'sayHello'],
    '~^product/all$~' => [\Src\Controllers\ProductController::class, 'all'],
    '~^cart$~' => [\Src\Controllers\CartController::class, 'view'],
    '~^cart/order$~' => [\Src\Controllers\CartController::class, 'order'],
    '~^cart/order/post$~' => [\Src\Controllers\CartController::class, 'orderPost'],
    '~^cart/add/(\d+)$~' => [\Src\Controllers\CartController::class, 'add'],
    '~^cart/add-to-cart/(\d+)$~' => [\Src\Controllers\CartController::class, 'addInCart'],
    '~^cart/delete/(\d+)$~' => [\Src\Controllers\CartController::class, 'delItem'],
    '~^cart/delete-in-cart/(\d+)$~' => [\Src\Controllers\CartController::class, 'deleteInCart'],
    '~^cart/delete-from-cart/(\d+)$~' => [\Src\Controllers\CartController::class, 'deleteInProducts'],
    '~^cart/clear$~' => [\Src\Controllers\CartController::class, 'clear'],
    '~^product/(.*)$~' => [\Src\Controllers\ProductController::class, 'view'],
    '~^search$~' => [\Src\Controllers\CategoriesController::class, 'search'],
    '~^admin$~' => [\Src\Controllers\Admin\CategoryAdminController::class, 'all'],
    '~^admin/category/all$~' => [\Src\Controllers\Admin\CategoryAdminController::class, 'all'],
    '~^admin/category/add$~' => [\Src\Controllers\Admin\CategoryAdminController::class, 'add'],
    '~^admin/category/(\d+)/view~' => [\Src\Controllers\Admin\CategoryAdminController::class, 'view'],
    '~^admin/category/(\d+)/edit~' => [\Src\Controllers\Admin\CategoryAdminController::class, 'edit'],
    '~^admin/category/(\d+)/delete~' => [\Src\Controllers\Admin\CategoryAdminController::class, 'delete'],
    '~^admin/product/all~' => [\Src\Controllers\Admin\ProductAdminController::class, 'all'],
    '~^admin/product/add~' => [\Src\Controllers\Admin\ProductAdminController::class, 'add'],
    '~^admin/product/(\d+)/view~' => [\Src\Controllers\Admin\ProductAdminController::class, 'view'],
    '~^admin/product/(\d+)/delete~' => [\Src\Controllers\Admin\ProductAdminController::class, 'delete'],
    '~^admin/product/(\d+)/edit~' => [\Src\Controllers\Admin\ProductAdminController::class, 'edit'],
    '~^admin/order/(\d+)/view~' => [\Src\Controllers\Admin\OrderAdminController::class, 'view'],
    '~^admin/order/(\d+)/edit~' => [\Src\Controllers\Admin\OrderAdminController::class, 'edit'],
    '~^admin/order/(\d+)/delete~' => [\Src\Controllers\Admin\OrderAdminController::class, 'delete'],
    '~^admin/order/all~' => [\Src\Controllers\Admin\OrderAdminController::class, 'all'],
    '~^admin/review/all$~' => [\Src\Controllers\Admin\ReviewAdminController::class, 'all'],
    '~^admin/review/(\d+)/view$~' => [\Src\Controllers\Admin\ReviewAdminController::class, 'view'],
    '~^admin/review/(\d+)/confirm$~' => [\Src\Controllers\Admin\ReviewAdminController::class, 'edit'],
    '~^admin/review/(\d+)/delete$~' => [\Src\Controllers\Admin\ReviewAdminController::class, 'delete'],

    '~^$~' => [\Src\Controllers\MainController::class, 'main'],

    '~^personal$~' => [\Src\Controllers\PersonalController::class, 'profile'],
    '~^personal/(\d+)/edit$~' => [\Src\Controllers\PersonalController::class, 'edit'],
    '~^personal/(\d+)/orders$~' => [\Src\Controllers\PersonalController::class, 'orders'],
    '~^personal/(\d+)/orders/view$~' => [\Src\Controllers\PersonalController::class, 'orderView'],
    
    '~^review/add$~' => [\Src\Controllers\MainController::class, 'add'],
    

];