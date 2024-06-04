<?php 
namespace Src\Models\Categories;

use InvalidArgumentException;
use Src\Models\Articles\ActiveRecordEntity;
use Src\Models\Products\Product;
use Src\Models\Users\User;
use Src\Services\Db;

class Category extends ActiveRecordEntity {
    public $id;
    public $title;
    public $description;
    public $parentId;

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setTitle($title): void {
        $this->title = $title;
    }

    public function setDescription($description): void {
        $this->description = $description;
    }

    public function setParentId($parentId): void {
        $this->parentId = $parentId;
    }

    protected static function getTableName(): string {
        return 'categories';
    }

    public function getParentId():int {
        return (int) $this->parentId;
    }

    public static function getSubcategories(): array {
        $db = DB::getInstance();
        return $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE parent_id > 0;', [], static::class
        );
    }

    public static function getCategories(): array {
        $db = DB::getInstance();
        return $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE parent_id = 0;', [], static::class
        );
    }

    


    public function updateCategory(array $fields): Category{
        if (empty($fields['title'])) {
            throw new InvalidArgumentException('Не передано название');
        }
        if (empty($fields['description'])) {
            throw new InvalidArgumentException('Не передано описание');
        }
        $this->setTitle($fields['title']);
        $this->setDescription($fields['description']);
        $this->save();
        return $this;
    }

    public static function createCategory(array $fields) : Category{
        if (empty($fields['title'])) {
            throw new InvalidArgumentException('Не передано название категории');
        }
        if (empty($fields['description'])) {
            throw new InvalidArgumentException('Не передано описание категории');
        }
        $category = new Category();
        $category->setTitle($fields['title']);
        $category->setDescription($fields['description']);
        $category->setParentId($fields['parent_id']);
        $category->save();
        return $category;
    }

}