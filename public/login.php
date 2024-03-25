<?php

use Controller\Auth;
use Controller\Request;
use Controller\Response;

$response = new Response();
$request = new Request();
$auth = new Auth();


if($auth->login($request->get('username'), $request->get('password'))) {
    return $response->success($auth->user());
} else {
    return $response->error(null);
}

