<?php
namespace App\Models;

use Illuminate\Database\Capsule\Manager as Capsule;

class Delivery
{
    public function add(int $order_id, string $delivery_address, string $delivery_date, string $delivery_code, string $memo) :Bool
    {
        $results = Capsule::insert('
        insert into delivery (order_id, delivery_address, delivery_date, delivery_code, memo) 
        values (?, ?, ?, ?, ?)', [$order_id, $delivery_address, $delivery_date, $delivery_code, $memo]);
        return $results;
    }

    public function findCode(string $delivery_code) :Array
    {
        $sql = "
            select d.delivery_code, cus.firstname, cus.lastname, d.delivery_address, d.delivery_date,
            group_concat(pro.name SEPARATOR '|') as products_name,
            group_concat(cap.quantity SEPARATOR '|') as products_quantity
                from `delivery` d
            join `orders` ord
                on ord.id = d.order_id
            join `customer` cus
                on cus.id = ord.customer_id
            join `cart_product` cap
                on cap.cart_id = ord.cart_id
            join `product` pro
                on pro.id = cap.product_id
            where d.delivery_code LIKE '$delivery_code'
        ";
        $results = Capsule::select($sql);
        $result = $results[0];
        $order = [];
        if($result->delivery_code != null) {
                $order = [
                    'delivery_code' => $result->delivery_code,
                    'firstname' => $result->firstname,
                    'lastname' => $result->lastname,
                    'delivery_address' => $result->delivery_address,
                    'delivery_date' => $result->delivery_date,
                    'products_name' => explode("|", $result->products_name),
                    'products_quantity' => explode("|", $result->products_quantity)
                ];
        }
        return $order;
    }
}