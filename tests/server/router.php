<?php

header('Content-Type:application/json;charset=UTF-8');

$data = ['ok' => true, 'result' => []];

$uri = ltrim($_SERVER['SCRIPT_NAME'], '/');
$payload = $_GET;
preg_match('#bot(.+)/(.+)#', $uri, $match);

$token = $match[1];
$method = $match[2];

switch ($method) {
    case 'getMe':
        $data['result'] = [
            'id' => 999,
            'first_name' => 'bot name',
            'username' => 'RealNameOfBot',
        ];
        break;

    default:
        $data['result'] = [
            'update_id' => 1,
            'message' => [
                'message_id' => 11,
                'from' => ['id' => 2],
                'chat' => ['id' => 3],
                'date' => time(),
                'text' => 'message string'
            ],
        ];
}

echo json_encode($data);
