<?php
namespace Src\Controllers;
use Src\Models\Users\User;
use Src\Models\Users\UserAuthService as GlobalUserAuthService;
use Src\Exceptions\InvalidArgumentException;

class UsersController extends Controller {
    public function signUp() {
        if (!empty($_POST)) {
            try {
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                http_response_code(404);
                echo json_encode(array("message" => $e->getMessage()));
            }
            if ($user instanceof User) {
                http_response_code(201);
                echo json_encode(array("message" => "User created"));
            }
        }
    }

    public function login() {
        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);
                GlobalUserAuthService::createToken($user);
                echo json_encode(array("message" => $user->getAuthToken(), "name" => $user->getNickName(), "email" => $user->getEmail()));
                exit;
            } catch (InvalidArgumentException $e) {
                http_response_code(404);
                echo json_encode(array("message" => $e->getMessage()));
            }
            http_response_code(200);
            echo json_encode(array("message" => "User logged in"));
        }
       http_response_code(404);
       echo json_encode(array("message" => "User not found"));

    }

    public function logout() {
        setcookie('token', '', -1, '/', '', false, true);
        echo json_encode(array("message" => "User logged out"));
    }

    public function getUser() {

        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Authorization header not found']);
            return;
        }

        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid Authorization header format']);
            return;
        }

        $user = User::getUserByToken($token);

        if ($user === null) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }
        if ($token === $user->getAuthToken()) {
            http_response_code(200);
            echo json_encode([
                'nickname' => $user->getNickName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'id' => $user->getId()
            ]);
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid token']);
        }
    }
}