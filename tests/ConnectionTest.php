<?php

class ConnectionTest extends PHPUnit_Framework_TestCase
{
    protected static $test_server_name = '127.0.0.1:9876';
    protected static $test_server_access = '/dev/null';

    public function testConnect()
    {
        $conn = new \Rde\Telegram\Connection('xxx', 'http://'.self::$test_server_name.'/bot');
        \Rde\Terminal::stdout(json_encode($conn->me));
        $this->assertEquals(999, $conn->me->{'id'});
    }

    /**
     * @expectedException RuntimeException
     */
    public function testTimeout()
    {
        $conn = new \Rde\Telegram\Connection('xxx', 'http://'.self::$test_server_name.'/bot');
        $conn->timeout(1);
        $conn->triggerTimeout('t=5');
    }

    /** @beforeClass */
    public static function startServer()
    {
        $check_server = function($server_name){
            list($ip, $port) = explode(':', $server_name, 2);
            exec("nc -v -w1 {$ip} {$port} 2>&1", $output);
            return isset($output[0]) && strpos($output[0], 'succeeded!');
        };

        $router = __DIR__.'/server/router.php';
        ! $check_server(self::$test_server_name) and
            exec($cmd = "php -S ".self::$test_server_name." {$router} > ".self::$test_server_access." &");

        isset($cmd) and \Rde\Terminal::stdout($cmd, "\e[33m");

        do {
            \Rde\Terminal::stdout('wait server start', "\e[33m");
            sleep(1);
        } while ( ! $check_server(self::$test_server_name));
        \Rde\Terminal::stdout('server start', "\e[32m");
    }

    /**
     * @afterClass
     */
    public static function shutdownServer()
    {
        exec("pkill -f 'php -S ".self::$test_server_name."'");
        \Rde\Terminal::stdout(PHP_EOL.'server shutdown', "\e[32m");
    }
}
