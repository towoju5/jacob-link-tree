<?php 

namespace Controller\Middlewares;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements IMiddleware {

    public function handle(Request $request): void 
    {
        if(validate_jwt_token($jwt_token, env('JSWT_SECRET'))) return true;
    }
}