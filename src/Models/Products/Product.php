<?php 
namespace Src\Models\Products;

use InvalidArgumentException;
use Src\Models\Articles\ActiveRecordEntity;
use Src\Models\Categories\Category;
use Src\Models\Users\User;
use Src\Services\Db;

class Product extends ActiveRecordEntity {
    public $id;
    public $title;
    public $content;
    public $description;
    public $price;
    public $oldPrice;
    public $category_id;
    public $img;
    public $is_offer;

    public function getTitle(): string {
        return $this->title;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getDescription(): string {
        return $this->description;
    }
    public function getContent(): string {
        return $this->content;
    }
    public function getPrice(): string {
        return $this->price;
    }
    public function getOldPrice(): string {
        return $this->oldPrice;
    }
    public function getCategoryId(): string {
        return $this->category_id;
    }
    public function getImg(): string {
        return $this->img;
    }
    public function getIsOffer(): string {
        return $this->isOffer;
    }
    public function setTitle($title): void {
        $this->title = $title;
    }
    public function setDescription($description): void {
        $this->description = $description;
    }
    public function setPrice($price): void {
        $this->price = $price;
    }
    public function setOldPrice($oldPrice): void {
        $this->oldPrice = $oldPrice;
    }
    public function setContent($content): void {
        $this->content = $content;
    }
    public function setCategoryId($category_id): void {
        $this->category_id = $category_id;
    }
    public function setImg($img): void {
        $this->img = $img;
    }
    public function setIsOffer($is_offer): void {
        $this->is_offer = $is_offer;
    }


    public static function getCategoryName(int $category_id): ?self {
        $db = DB::getInstance();
        $entities = $db->query(
            'SELECT * FROM `' . Category::getTableName() . '` WHERE id=:id;', [':id' => $category_id], static::class
        );
        return $entities ? $entities[0] : null; 
    }

    public static function getProductsByCategoryId(int $category_id): array {
        $db = DB::getInstance();
        return $db->query(
            'SELECT * FROM `' . Product::getTableName() . '` WHERE category_id=:id;', [':id' => $category_id], static::class
        );
    }


    public static function getSaleProducts(): array {
        $db = DB::getInstance();
        return $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE price < 4000;', [], static::class
        );
    }

    public static function createProduct(array $fields): Product {
        if (empty($fields['title'])) {
            throw new InvalidArgumentException('Не передано название товара');
        }
        if (empty($fields['description'])) {
            throw new InvalidArgumentException('Не передано описание товара');
        }
        if (empty($fields['content'])) {
            throw new InvalidArgumentException('Не передано предназначение товара');
        }
        if (empty($fields['price'])) {
            throw new InvalidArgumentException('Не передана цена товара');
        }
        if (empty($fields['oldPrice'])) {
            throw new InvalidArgumentException('Не передана старая цена товара');
        }
        if (empty($fields['category_id'])) {
            throw new InvalidArgumentException('Не передано категория товара');
        }
        if (empty($fields['img'])) {
            throw new InvalidArgumentException('Не передана картинка товара');
        }
        $product = new Product();
        $product->setTitle($fields['title']);
        $product->setDescription($fields['description']);
        $product->setCategoryId($fields['category_id']);
        $product->setContent($fields['content']);
        $product->setPrice($fields['price']);
        $product->setOldPrice($fields['oldPrice']);
        $product->setImg($fields['img']);
        $product->save();
        return $product;
    }
    public function setOffer(): Product {
        $this->setIsOffer(1);
        $this->save();
        return $this;
    }

    public static function getOrdersProducts(array $orders): array {
        $db = DB::getInstance();
        $products = [];
        foreach($orders as $order) {
            $products[] = $db->query(
                'SELECT * FROM ' . Product::getTableName() . ' WHERE id=:id;', 
                [':id' => $order->getProductId()], 
                static::class
            ); 
        } return $products;
    
        
    }


    public function updateProduct(array $fields): Product {
        if (empty($fields['title'])) {
            throw new InvalidArgumentException('Не передано название товара');
        }
        if (empty($fields['description'])) {
            throw new InvalidArgumentException('Не передано описание товара');
        }
        if (empty($fields['content'])) {
            throw new InvalidArgumentException('Не передано предназначение товара');
        }
        if (empty($fields['price'])) {
            throw new InvalidArgumentException('Не передана цена товара');
        }
        if (empty($fields['oldPrice'])) {
            throw new InvalidArgumentException('Не передана старая цена товара');
        }
        if (empty($fields['category_id'])) {
            throw new InvalidArgumentException('Не передано категория товара');
        }
        if (empty($fields['img'])) {
            throw new InvalidArgumentException('Не передана картинка товара');
        }
        $this->setTitle($fields['title']);
        $this->setDescription($fields['description']);
        $this->setCategoryId($fields['category_id']);
        $this->setContent($fields['content']);
        $this->setPrice($fields['price']);
        $this->setOldPrice($fields['oldPrice']);
        $this->setImg($fields['img']);
        $this->save();
        return $this;
    }
    

    public static function search($searchString) {
        $db = Db::getInstance();
        $products = $db->query("SELECT * FROM " . static::getTableName() . " WHERE title LIKE '%$searchString%'", [], static::class);
        if ($products) {
            return $products;
        } else {
            return null;
        }
        
    }

    protected static function getTableName(): string {
        return 'products';
    }

}