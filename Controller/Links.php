<?php

namespace Controller;

use Controller\Base\BaseController;
use Controller\Base\Database;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Links extends BaseController {
 
    private $db;
    public $request;

    public function __construct() {
        validate_jwt_token(get_bearer_token());
        $this->db = new Database();
    }

    /**
     * Retrieve all user links
     */
    public function get() {
        return $this->db->select('user_links', ['user_id' => $this->getUserId()]);
    }

    /**
     * Store new link
     * @param mixed $data
     */
    public function store($data) {
        return $this->db->insert('user_links', $data);
    }

    /**
     * Retrieve single user links
     * @param int $id
     */
    public function show($id) {
        return $this->db->select('user_links', ['id' => $id, 'user_id' => $this->getUserId()]);
    }

    /**
     * Update link
     * @param array $data
     * @param int $id
     */
    public function update($data, $id) {
        return $this->db->update('user_links', $data, ['id' => $id, 'user_id' => $this->getUserId()]);
    }

    /**
     * Delete link
     * @param int $id
     */
    public function destroy($id) {
        return $this->db->delete('user_links', ['id' => $id, 'user_id' => $this->getUserId()]);
    }
}
