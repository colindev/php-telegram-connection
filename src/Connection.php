<?php namespace Rde\Telegram;

class Connection
{
    public $me;
    private $url;

    public function __construct($token, $target = 'https://api.telegram.org/bot')
    {
        $this->url = $target.$token;

        $this->me = $this->getMe();
    }

    protected function resolveData($str)
    {
        $res = json_decode($str);

        if ($res && $res->{'ok'}) return $res->{'result'};

        return false;
    }

    protected function resolveUrl($api, $params)
    {
        ! empty($params) and $payload = is_string($params) ? $params : http_build_query($params);

        return "{$this->url}/{$api}".(isset($payload) ? "?{$payload}" : '');
    }

    public function __call($method, $params)
    {
        $url = $this->resolveUrl($method, array_shift($params));

        return $this->resolveData(file_get_contents($url));
    }
}
