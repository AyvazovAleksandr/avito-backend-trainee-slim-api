<?php
namespace App\Controllers;

use App\Classes\Delivery;
use App\Models\Order;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OrdersController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * Метод для создания заказа
     */

    public function createOrder(Request $request, Response $response) {
        $params = $request->getQueryParams();
        $delivery_address = $params['delivery_address'] ?? '';
        $cart_id = $params['cart_id'] ?? '';
        //id клиента по умолчанию, для примера работы API
        //в рабочей версии будет получен настойщий id пользователя, через Auth
        $customer_id = 1;
        $fc_cart_id = substr($cart_id , 0, 1);
        if($fc_cart_id == '0') {
            $response = $response->withStatus(400);
            return $response;
        }
        preg_match('/[а-яА-ЯЁёa-zA-Z0-9_]+/u', $delivery_address, $matches_delivery);
        preg_match('/^[0-9]+$/', $cart_id , $matches_cart_id);
        if(($matches_delivery) && ($matches_cart_id)) {
            $delivery_object = new Delivery();
            $delivery_cost = $delivery_object->getCostDelivery($cart_id, $delivery_address);
            $order = new Order();
            $result = $order->add($cart_id, $delivery_cost, $customer_id);
            if($result) {
                $response = $response->withStatus(201);
                return $response;
            } else {
                $response = $response->withStatus(500);
                return $response;
            }
        } else {
            $response = $response->withStatus(400);
            return $response;
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * Метод для получения списка всех заказов
     */
    public function getAll(Request $request, Response $response) {
        $params = $request->getQueryParams();
        $manager_key = $params['manager_key'] ?? '';
        if($manager_key == _MANAGER_KEY_) {
            $order = new Order();
            $orders = $order->all();
            $response = $response->withStatus(200);
            $response->getBody()->write(json_encode($orders, JSON_UNESCAPED_UNICODE));
            return $response;
        } else {
            $response = $response->withStatus(403);
            return $response;
        }

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * Метод для получения подробной информации по заказу
     */

    public function getOrder(Request $request, Response $response, array $args) {
        $params = $request->getQueryParams();
        $manager_key = $params['manager_key'] ?? '';
        if($manager_key == _MANAGER_KEY_) {
            $id = $args['id'];
            $fc = substr($id , 0, 1);
            if($fc == '0') {
                $response = $response->withStatus(400);
                return $response;
            }
            preg_match('/^[0-9]+$/', $id, $matches);
            if($matches){
                $order = new Order();
                $orders = $order->find($id);
                $response = $response->withStatus(200);
                $response->getBody()->write(json_encode($orders, JSON_UNESCAPED_UNICODE));
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