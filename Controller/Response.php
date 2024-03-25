<?php

namespace Controller;

class Response {
    public static function success($data) {
        return json_encode([
            "success"   => true,
            "status"    => 200,
            "data"      => $data
        ], JSON_PRETTY_PRINT);
    }

    public static function error($data) {
        return json_encode([
            "success"   => false,
            "status"    => 400,
            "data"      => $data
        ], JSON_PRETTY_PRINT);
    }
}