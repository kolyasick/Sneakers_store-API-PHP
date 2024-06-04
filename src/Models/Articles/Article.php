<?php 
namespace Src\Models\Articles;

use InvalidArgumentException;
use Src\Models\Articles\ActiveRecordEntity;
use Src\Models\Users\User;
use Src\Services\Db;

class Article extends ActiveRecordEntity {
    protected $name;
    protected $text;
    protected $authorId;
    protected $createdAt;

    public function getName(): string {
        return $this->name;
    }

    public function getText(): string {
        return $this->text;
    }

    protected static function getTableName(): string {
        return 'articles';
    }

    public function getAuthorId():int {
        return (int) $this->authorId;
    }

    public function setAuthor(User $author): void {
        $this->authorId = $author->getId();
    }

    public function getAuthor(): User {
        return User::getById($this->authorId);
    }

    public function setText($text): void {
        $this->text = $text;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public static function createArticle(array $fields, User $author) : Article{
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }
        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }
        $article = new Article();
        $article->setAuthor($author);
        $article->setName($fields['name']);
        $article->setText($fields['text']);
        $article->save();
        return $article;
    }
    
    public function updateArticle(array $fields): Article{
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }
        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }
        $this->setName($fields['name']);
        $this->setText($fields['text']);
        $this->save();
        var_dump($fields);
        return $this;
    }
}