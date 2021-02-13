<?php
namespace App\Classes;


class Delivery
{
    /**
     * @param int $cart_id
     * @param string $delivery_address
     * @return String
     * Метод для расчета стоимости доставки
     * (т.к. это пример API, то отдаем рандомную стоимость)
     */
    public function getCostDelivery(int $cart_id, string $delivery_address) : Int
    {
        return rand(10, 100);
    }
}