<?php 
namespace Src\Models\Reviews;

use Src\Exceptions\InvalidArgumentException;
use Src\Models\Articles\ActiveRecordEntity;
use Src\Models\Categories\Category;
use Src\Models\Users\User;
use Src\Services\Db;

class Review extends ActiveRecordEntity {
    protected $name;
    protected $text;
    protected $rating;
    protected $is_confirmed;

    public function getIsConfirmed(): string {
        return $this->is_confirmed;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getText(): string {
        return $this->text;
    }
    public function getRating(): string {
        return $this->rating;
    }

    public function setName($name): void {
        $this->name = $name;
    }
    public function setText($text): void {
        $this->text = $text;
    }
    public function setRating($rating): void {
        $this->rating = $rating;
    }
    public function setIsConfirmed($is_confirmed): void {
        $this->is_confirmed = $is_confirmed;
    }

    public static function createReview(array $fields) {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано имя');
        }
        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст');
        }
        if (empty($fields['rating'])) {
            throw new InvalidArgumentException('Не передан рейтинг');
        }
        $review = new Review();
        $review->name = $fields['name'];
        $review->text = $fields['text'];
        $review->rating = $fields['rating'];
        $review->save();
        return $review;
    }

    public function updateReview(array $fields): Review {
        if (empty($fields['is_confirmed'])) {
            throw new InvalidArgumentException('Ошибка входных данных');
        }
        $this->setIsConfirmed($fields['is_confirmed']);
        $this->save();
        return $this;
    }
    

    protected static function getTableName(): string {
        return 'reviews';
    }

}