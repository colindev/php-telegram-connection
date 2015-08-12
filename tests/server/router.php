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

    case 'triggerTimeout':
        sleep((int) $payload['t']);
        return;

    default:
        // empty
}

echo json_encode($data);
