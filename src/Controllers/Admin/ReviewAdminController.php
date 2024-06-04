<?php


namespace Src\Controllers\Admin;
use Src\Controllers\Controller;
use Src\Models\Reviews\Review;
use Src\Exceptions\InvalidArgumentException;
use Src\Exceptions\NotFoundException;


class ReviewAdminController extends Controller {
    public function all() {
        $reviews = Review::findAll();

        $this->view->renderAdmin('Admin/Review/all.php', ['reviews' => $reviews]);
    }

    public function view($id){
        $review = Review::getByID($id);

        if(empty($review)){
            return false;
        }
        $this->view->renderAdmin('Admin/Review/view.php', ['review' => $review]);
    }

    public function edit(int $id): void {
        $review = Review::getById($id);

        if ($review === null) {
            throw new NotFoundException();
        }
        if (!empty($_POST)) {
            try {
                $review->updateReview($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderAdmin('Admin/Review/view.php', ['error'
                => $e->getMessage(), 'review' => $review]);
                return;
            }
            header('Location: /admin/review/all');
            exit();
        }
        $this->view->renderAdmin('Admin/Review/view.php', ['review' => $review]);
    }

    public function delete(int $id): void {
        $review = Review::getById($id);
        if ($review === null) {
            throw new NotFoundException();
        }
        $review->delete();
        header('Location: /admin/review/all');
    }
}