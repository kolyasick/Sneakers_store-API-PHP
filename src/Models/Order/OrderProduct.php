<?php 
namespace Src\Models\Order;
use Src\Models\Articles\ActiveRecordEntity;
use Src\Exceptions\InvalidArgumentException;
use Src\Models\Products\Product;
use Src\Services\Db;

class OrderProduct extends ActiveRecordEntity {
    protected $order_id;
    protected $product_id;
    protected $title;
    protected $price;
    protected $qty;
    protected $total;

    public function getTitle() {
       return $this->title;
    }
    public function getPrice() {
        return $this->price;
    }
    public function getQty() {
        return $this->qty;
    }
    public function getTotal() {
        return $this->total;
    }
    
    public function getProductId() {
        return $this->product_id;
    }
    public function getOrderId() {
        return $this->order_id;
    }

    public static function getOrdersByCategoryId(int $category_id): array {
        $db = DB::getInstance();
        return $db->query(
            'SELECT * FROM `' . OrderProduct::getTableName() . '` WHERE order_id=:id;', [':id' => $category_id], static::class
        );
    }

    public static function saveOrderProducts(string $cartItemsJson, $orderId) {
        // Декодируем JSON строку в массив
        $cartItems = json_decode($cartItemsJson, true);

        // Проверяем, что декодирование прошло успешно
        if ($cartItems === null && json_last_error() !== JSON_ERROR_NONE) {
            die("Ошибка декодирования JSON: " . json_last_error_msg());
        }

        // Проверяем, что массив с элементами корзины не пуст
        if (empty($cartItems)) {
            return false;
        }

        foreach ($cartItems as $product) {
            $orderProduct = new OrderProduct;
            $orderProduct->order_id = $orderId;
            $orderProduct->product_id = $product['id'];
            $orderProduct->title = $product['title'];
            $orderProduct->price = $product['price'];
            $orderProduct->qty = $product['quantity']; // Используем 'quantity' вместо 'qty'
            $orderProduct->total = $product['quantity'] * $product['price'];
            $orderProduct->save();
        }

        return true;
    }



    protected static function getTableName(): string {
        return 'order_product';
    }

}