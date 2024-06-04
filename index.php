<?php

// Заголовки для обработки CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Access-Control-Allow-Credentials: true');

// Обработка preflight-запросов
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');

try {
    spl_autoload_register();
    session_start();
    $route = $_GET['route'] ?? '';
    $routes = require __DIR__ . '/src/Config/routes.php';

    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches); // matches - массив совпадений
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }
    if (!$isRouteFound) {
        throw new \Src\Exceptions\NotFoundException();
    }
    unset($matches[0]);
    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];
    $controller = new $controllerName();
    $controller->$actionName(...$matches);
} catch (\Src\Exceptions\DbException $e) {
    $view = new \Src\Views\View('default');
    $view->renderHtml('Errors/500.php', ['error' => $e->getMessage()], 500);
} catch (\Src\Exceptions\NotFoundException $e) {
    $view = new \Src\Views\View('default');
    $view->renderHtml('Errors/404.php', ['error' => $e->getMessage()], 404);
} catch (\Src\Exceptions\UnauthorizedException $e) {
    $view = new \Src\Views\View('default');
    $view->renderHtml('Errors/401.php', ['error' => $e->getMessage()], 401);
}
