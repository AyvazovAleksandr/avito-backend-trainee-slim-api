<?php
namespace App\Classes;

class RateLimitMiddleware
{
    /**
     * @var null|string
     */
    public $host = null;

    /**
     * @var null|string
     */
    public $port = null;

    /**
     * @var null
     */
    public $pass = null;

    protected $handle = null;

    protected $maxRequests = 10;

    protected $seconds = 60;

    /**
     * RateLimitMiddleware constructor.
     * @param string $host
     * @param string $port
     * @param null $pass
     */
    public function __construct($host = 'localhost', $port = '6379', $pass = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->pass = $pass;

        //Создаем подключение к Redis
        $this->handle = new \TinyRedisClient(sprintf("%s:%s", $this->host, $this->port));

        //Если задан пароль, подключаемся с паролем
        if ($this->pass !== null){
            $this->auth();
        }
    }

    /**
     * @param int $maxRequests
     * @param int $seconds
     * Устанавливаем лимиты для работы с API
     */
    public function setRequestsPerSecond($maxRequests = 10, $seconds = 60)
    {
        if (!is_int($maxRequests)){
            throw new \InvalidArgumentException;
        }

        if (!is_int($seconds)){
            throw new \InvalidArgumentException;
        }

        $this->maxRequests = $maxRequests;
        $this->seconds = $seconds;
    }

    /**
     * Авторизация с Redis
     */
    public function auth()
    {
        $this->handle->auth($this->pass);
    }

    /**
     * @param $request
     * @param $response
     * @return bool
     * Магический метод, если лимит запросов исчерпан, вернет false, иначе true
     */
    public function __invoke($request, $response)
    {
        if (count($this->handle->keys(sprintf("%s*", str_replace('.', '', $_SERVER['REMOTE_ADDR'])))) >= $this->maxRequests) {
            return false;
        } else {
            $key = sprintf("%s%s", str_replace('.', '', $_SERVER['REMOTE_ADDR']), mt_rand());
            $this->handle->set($key, time());
            $this->handle->expire($key, $this->seconds);
            return true;
        }
    }
}