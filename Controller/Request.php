<?php

namespace Controller;

class Request {
    public $request;

    public function __construct() {
        $this->request = [];
    }

    public function post($keys = null, $data = null) {
        if (is_array($keys)) {
            foreach ($_REQUEST as $key => $value) {
                $this->request[$key] = $value;
            }
            return $this->request;
        }
        return $this->get($keys);
    }

    public function get($key) {
        return $_REQUEST[$key];
    }
}
