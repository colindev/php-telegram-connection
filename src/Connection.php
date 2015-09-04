<?php namespace Rde\Telegram;

class Connection
{
    public $me;
    private $url;
    private $timeout = 600;

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

        return $this;
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

        exec("wget -t {$this->timeout} --timeout {$this->timeout} -qO- '{$url}'", $out);
        
        if (empty($out)) throw new \RuntimeException();

        return $this->resolveData(join('', $out));
    }
}
