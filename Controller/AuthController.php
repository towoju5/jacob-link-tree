<?php

namespace Controller;
use Controller\Base\Auth;
use Controller\Base\Controller as BaseController;

class AuthController extends BaseController {

    public function __construct() {
        //
    }
    
    public function login() {
        try {
            $auth = new Auth(); 
            $check = $auth->login(input('username'), input('password'));
            if($check) {
                return Response::success(['user' => $check]);
            }
        } catch (\Throwable $th) {
            return Response::error(['error' => $th->getMessage()]);
        }
    }

    public function register() {
        //
    }

    public function logout() {
        //
    }
}