<?php namespace Rde\Telegram;

class Connection
{
    public $me;
    private $url;
    private $timeout = 0;
    private $method = 'GET';

    public $verbose = array();

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

    public function method($type = null) 
    {
        if (null !== $type) {
            $type = strtoupper($type);
            switch ($type) {
                case 'GET':
                case 'POST':
                    $this->method = $type;
                    break;
                default:
                    throw new \InvalidArgumentException("method 僅能使用 GET/POST");
            }
        }
        
        return $this->method;
    }

    protected function resolveData($str)
    {
        $res = json_decode($str);
        if ($res && $res->{'ok'}) return $res->{'result'};

        return false;
    }

    protected function resolveUrl($api, $params)
    {
        $info = explode('/', $this->url);
        $socket_type = null;
        $port = null;
        $payload = null;

        switch ($info[0]) {
            case 'http:':
                $socket_type = 'tcp';
                $port = 80;
                break;

            case 'https:':
                $socket_type = 'ssl';
                $port = 443;
                break;
        }

        ! empty($params) and $payload = is_string($params) ? $params : http_build_query($params);

        $ret = array(
            'socket_type' => $socket_type,
            'host' => $info[2],
            'port' => $port,
            'uri' => join('/', array_slice($info, 3))."/{$api}",
            'payload' => $payload,
        );

        $this->verbose[] = "uri: {$ret['uri']}";
        $this->verbose[] = "payload: {$ret['payload']}";

        return $ret;
    }

    protected function resolveRequestPost($f)
    {
        $len = strlen($f['payload']);
        $stream = array();
        $stream[] = "POST /{$f['uri']} HTTP/1.1";
        $stream[] = "Host: {$f['host']}";
        $stream[] = "Accept: */*";
        $stream[] = "Content-Length: {$len}";
        $stream[] = "Content-Type: application/x-www-form-urlencoded; charset=utf8";
        $stream[] = "Connection: close";
        $stream[] = "\r\n";
        $stream[] = $f['payload'];
    
        return join("\r\n", $stream);
    }

    protected function resolveRequestGet($f)
    {
        $stream = array();
        $has_args = $f['payload']? '?' : '';
        $stream[] = "GET /{$f['uri']}{$has_args}{$f['payload']} HTTP/1.1";
        $stream[] = "Host: {$f['host']}";
        $stream[] = "Accept: */*";
        $stream[] = "Connection: close";
        $stream[] = "\r\n";
    
        return join("\r\n", $stream);
    }

    protected function resolveRequest($f) 
    {
        $fp = stream_socket_client(
            $remote = "{$f['socket_type']}://{$f['host']}:{$f['port']}", 
            $errno, 
            $errstr);
        
        $this->verbose[] = "remote: {$remote}";
        $this->verbose[] = "timeout: {$this->timeout}";

        $this->timeout && stream_set_timeout($fp, $this->timeout);

        if ( ! $fp) {
            throw new \RuntimeException($errstr, $errno);
        }

        fwrite($fp, call_user_func(array($this, 'resolveRequest'.ucwords(strtolower($this->method()))), $f));

        $string = stream_get_contents($fp, -1);
        $meta = stream_get_meta_data($fp);

        $this->verbose[] = "meta: ".json_encode($meta);
        $this->verbose[] = "response: {$string}";

        fclose($fp);
        if ($meta['timed_out']) {
            throw new \RuntimeException("timeout({$this->timeout})!");
        }
        
        list($header, $body) = preg_split("/\r\n\r\n/", $string);

        return $body;
    }

    public function __call($method, $params)
    {
        $this->verbose = array();

        $info = $this->resolveUrl($method, array_shift($params));
        $data = $this->resolveRequest($info);

        return $this->resolveData($data);
    }
}
