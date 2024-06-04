<?php

namespace Src\Controllers;

use InvalidArgumentException;
use Src\Models\Users\UserAuthService;
use Src\Views\View;
use Src\Models\Articles\Article;
use Src\Models\Users\User;
use Src\Exceptions\NotFoundException;
use Src\Exceptions\UnauthorizedException;
use UnderflowException;

class ArticlesController extends Controller {
    public function all() {
        $articles = Article::findAll();
        if ($articles) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($articles);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No articles found"));
        }
    }
    public function view(int $articleId) {
        $article = Article::getByID($articleId);
        if ($article) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($article);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No articles found"));
        }
    }   

    public function edit(int $articleId): void {
        $article = Article::getById($articleId);
        if ($article === null) {
            http_response_code(404);
            echo json_encode(array("message" => "No articles found"));
        }
        if ($this->user === null) {
            http_response_code(404);
            echo json_encode(array("message" => "User not registered"));
        } 
        if (!empty($_POST)) {
            try {
                $article->updateArticle($_POST);
            } catch (InvalidArgumentException $e) {
                http_response_code(404);
                echo json_encode(array("message" => $e->getMessage()));
            }
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array("message" => "Article updated"));
    }

    public function add(): void {
        if ($this->user === null) {
            http_response_code(404);
            echo json_encode(array("message" => "User not registered"));
        }
        if (!empty($_POST)) {
            try{
                $article = Article::createArticle($_POST,
                $this->user);
            } catch (InvalidArgumentException $e) {
                http_response_code(404);
                echo json_encode(array("message" => $e->getMessage()));
            }
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array("message" => "Article created"));
    }

    public function delete(int $articleId): void {
        $article = Article::getById($articleId);
        if ($article === null) {
            http_response_code(404);
            echo json_encode(array("message" => "No articles found"));
        }
        $article->delete();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array("message" => "Article deleted"));
    }

}