<?php
namespace App\Models;

use Illuminate\Database\Capsule\Manager as Capsule;


class Order
{

    /**
     * @return Array
     * Метод получения списка всех заказов
     */
    public function all() :Array
    {
       $sql = "
            select ord.id, cus.firstname, cus.lastname, ord.total_cost, ord.delivery_cost, ord.cart_cost
                from `orders` ord
            join `customer` cus
                on cus.id = ord.customer_id
        ";
       $results = Capsule::select($sql);
       $orders = [];
       foreach ($results as $result) {
           $orders[] = [
               'id' => $result->id,
               'firstname' => $result->firstname,
               'lastname' => $result->lastname,
               'total_cost' => $result->total_cost,
               'delivery_cost' => $result->delivery_cost,
               'cart_cost' => $result->cart_cost,
           ];
       }
       return $orders;
    }

    /**
     * @param int $id
     * @return Array
     * Метод получения детальной информации по заказу
     */

    public function find(int $id) :Array
    {
        $sql = "
            select ord.id, cus.firstname, cus.lastname, c.cost as cart_cost, 
            group_concat(pro.name SEPARATOR '|') as products_name,
            group_concat(pro.id SEPARATOR '|') as products_id,
            group_concat(pro.price SEPARATOR '|') as products_price,
            group_concat(cap.quantity SEPARATOR '|') as quantity_cart
                from `orders` ord
            join `customer` cus
                on cus.id = ord.customer_id
            join `cart` c
                on c.id = ord.cart_id
            join `cart_product` cap
                on cap.cart_id = ord.cart_id
            join `product` pro
                on pro.id = cap.product_id
            where ord.id = $id
            group by ord.id
        ";

        $results = Capsule::select($sql);
        $orders = [];
        foreach ($results as $result) {
            $orders[] = [
                'id' => $result->id,
                'firstname' => $result->firstname,
                'lastname' => $result->lastname,
                'cart_cost' => $result->cart_cost,
                'products_name' => explode("|", $result->products_name),
                'products_id' => explode("|", $result->products_id),
                'products_price' => explode("|", $result->products_price),
                'quantity_cart' => explode("|", $result->quantity_cart)
            ];
        }
        return $orders;
    }

    public function add(int $cart_id, int $delivery_cost, int $customer_id) :Bool
    {
        $cart_cost = 10;
        $total_cost = $cart_cost + $delivery_cost;
        $results = Capsule::insert('
        insert into orders (customer_id, cart_id, total_cost, delivery_cost, cart_cost) 
        values (?, ?, ?, ?, ?)', [$customer_id, $cart_id, $total_cost, $delivery_cost, $cart_cost]);
        return $results;
    }
}