<?php namespace Rde\Telegram;

class Connection
{
    public $me;
    private $url;
    private $timeout = 0;

    public function __construct($token, $target = 'https://api.telegram.org/bot')
    {
        $this->url = $target.$token;

        $this->me = $this->__call('getMe', array());
    }

    public function getMe()
    {
        return $this->me;
    }

    public function timeout($t)
    {
        $this->timeout = (int) $t;
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

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        $ret = curl_exec($ch);

        if ($err = curl_error($ch)) {
            throw new \RuntimeException($err);
        }

        return $this->resolveData($ret);
    }
}
