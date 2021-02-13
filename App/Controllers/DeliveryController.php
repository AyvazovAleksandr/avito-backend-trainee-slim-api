<?php
namespace App\Controllers;

use App\Classes\Delivery;
use App\Models\Delivery as ModelsDelivery;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeliveryController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * Метод для расчета стоимости доставки
     */
    public function getCalculate(Request $request, Response $response) {
        $params = $request->getQueryParams();
        $delivery_address = $params['delivery_address'] ?? '';
        $cart_id = $params['cart_id'] ?? '';
        $fc_cart_id = substr($cart_id , 0, 1);
        if($fc_cart_id == '0') {
            $response = $response->withStatus(400);
            return $response;
        }
        preg_match('/[а-яА-ЯЁёa-zA-Z0-9_]+/u', $delivery_address, $matches_delivery);
        preg_match('/^[0-9]+$/', $cart_id , $matches_cart_id);
        if(($matches_delivery) && ($matches_cart_id)){
            $delivery_object = new Delivery();
            $delivery = array(
                'delivery_cost' => $delivery_object->getCostDelivery($cart_id, $delivery_address) . " $"
            );
            $response = $response->withStatus(200);
            $response->getBody()->write(json_encode($delivery, JSON_UNESCAPED_UNICODE));
            return $response;
        } else {
            $response = $response->withStatus(400);
            return $response;
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * Метод для создания доставки
     */
    public function createDelivery(Request $request, Response $response) {
        $params = $request->getQueryParams();
        $order_id = $params['order_id'] ?? '';
        $delivery_address = $params['delivery_address'] ?? '';
        $delivery_date = $params['delivery_date'] ?? '';
        $memo = $params['memo'] ?? '';
        $manager_key = $params['manager_key'] ?? '';
        if($manager_key == _MANAGER_KEY_) {

            $fc_order_id= substr($order_id , 0, 1);
            if($fc_order_id == '0') {
                $response = $response->withStatus(400);
                return $response;
            }
            preg_match('/[а-яА-ЯЁёa-zA-Z0-9_]+/u', $delivery_address, $matches_delivery);
            preg_match('/^[0-9]+$/', $order_id , $matches_order_id);
            if(($matches_delivery) && ($matches_order_id)){
                $alphabet = range('A',  'Z');
                $alphabet = array_slice($alphabet, 0, 8);
                shuffle($alphabet);
                //получаем уникальный код доставки, который передадим курьеру
                $delivery_code = implode('',$alphabet);
                $delivery = new ModelsDelivery();
                $result = $delivery->add($order_id, $delivery_address, $delivery_date, $delivery_code, $memo);
                if($result) {
                    $response = $response->withStatus(201);
                    $response->getBody()->write(json_encode(array('delivery_code' => $delivery_code), JSON_UNESCAPED_UNICODE));
                    return $response;
                } else {
                    $response = $response->withStatus(500);
                    return $response;
                }
            } else {
                $response = $response->withStatus(400);
                return $response;
            }

        } else {
            $response = $response->withStatus(403);
            return $response;
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * Метод для получения информации о доставки, для курьера
     */

    public function searchDeliveryCourier(Request $request, Response $response) {
        $params = $request->getQueryParams();
        $courier_key = $params['courier_key'] ?? '';
        $delivery_code = $params['delivery_code'] ?? '';
        if($courier_key == _COURIER_KEY_) {
            preg_match('/^[A-Z]+$/u', $delivery_code, $matches_delivery_code);
            if ($matches_delivery_code) {
                $delivery = new ModelsDelivery();
                $result = $delivery->findCode($delivery_code);
                $response = $response->withStatus(200);
                $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));
                return $response;
            } else {
                $response = $response->withStatus(400);
                return $response;
            }
        } else {
            $response = $response->withStatus(403);
            return $response;
        }
    }
}