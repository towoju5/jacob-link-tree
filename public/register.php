<?php

use Controller\Auth;
use Controller\Request;
use Controller\Response;

$response = new Response();
$request = new Request();
$auth = new Auth();

if($auth->register($request->get('username'), $request->get('password'), $request->get('email'))) {
    return $response->success($auth->user());
} else {
    return $response->error(null);
}

