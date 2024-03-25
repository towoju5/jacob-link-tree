<?php
require __DIR__.'/vendor/autoload.php';
use Controller\Auth;
use Middlewares\Utils\Dispatcher;
use Pecee\SimpleRouter\SimpleRouter;


/* Load external routes file */
require_once 'routes/web.php';

/**
 * The default namespace for route-callbacks, so we don't have to specify it each time.
 * Can be overwritten by using the namespace config option on your routes.
 */

try {
    SimpleRouter::start();
} catch (\Throwable $th) {
    echo $th->getMessage();
    return false;
}