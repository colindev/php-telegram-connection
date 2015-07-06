<?php namespace Rde\Telegram;

class Connection
{
    public $me;
    private $url;

    public function __construct($token)
    {
        $this->url = 'https://api.telegram.org/bot'.$token;

        $this->me = $this->getMe();
    }

    protected function resolveData($str)
    {
        $res = json_decode($str);

        if ($res && $res->{'ok'}) return $res->{'result'};

        return false;
    }

    public function __call($method, $params)
    {
        $params = array_shift($params);
        if ($params) {
            $payload = is_string($params) ? $params : http_build_query($params);
        }

        $filename = "{$this->url}/{$method}".(isset($payload) ? "?{$payload}" : '');

        return $this->resolveData(file_get_contents($filename));
    }
}
