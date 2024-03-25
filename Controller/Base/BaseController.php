<?php 

namespace Controller\Base;
use Controller\Request;


class BaseController {
    public function __construct() {
        $request = new Request;
    }

    /**
     * Get currently logged in user ID
     */
    public function getUserId()
    {
        return 1;
    }

}