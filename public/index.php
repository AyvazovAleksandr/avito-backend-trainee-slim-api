<?php
require '../vendor/autoload.php';
use Psr\Http\Message\ResponseInterface as PrsResponse;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use App\Classes\RateLimitMiddleware;


$app = AppFactory::create();

/**
 * @param Request $request
 * @param RequestHandler $handler
 * @return bool|PrsResponse
 * Создаем посредника, для установки Rate Limit
 */
$RateLimitMiddleware = function (Request $request, RequestHandler $handler) {
    $response = $handler->handle($request);
    $rateLimitMiddleware = new RateLimitMiddleware('redis', '6379');
    $rateLimitMiddleware->setRequestsPerSecond(10, 60);
    $limits = $rateLimitMiddleware($request, $response);
    if($limits){
        return $response;
    } else {
        header("HTTP/1.1 429 Too Many Requests");
        exit;
    }
};

$app->add($RateLimitMiddleware); // Добавляем посредника
$app->get('/', function (Request $request, PrsResponse $response) {
    $response->getBody()->write("Rest Api Slim");
    return $response;
});

$app->get('/delivery/calculate', '\App\Controllers\DeliveryController::getCalculate');

$app->post('/delivery/create', '\App\Controllers\DeliveryController::createDelivery');

$app->get('/delivery/courier/find', '\App\Controllers\DeliveryController::searchDeliveryCourier');

$app->post('/orders/create', '\App\Controllers\OrdersController::createOrder');

$app->get('/orders/get/all', '\App\Controllers\OrdersController::getAll');

$app->get('/orders/get/{id}', '\App\Controllers\OrdersController::getOrder');

// Run app
$app->run();