<?php

namespace Controller\Base;

use PDO;
use Exception;

class Database {
    private $conn;

    public function __construct() {
        try {
            $host = env("MYSQL_HOST");
            $username = env("MYSQL_USERNAME", "root");
            $password = env("MYSQL_PASSWORD", null);
            $database = env("MYSQL_DATABASE", "jacob");
            $dsn = "mysql:host=$host;dbname=$database";
            $this->conn = new PDO($dsn, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $table = "CREATE TABLE IF NOT EXISTS users (
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                linkedin VARCHAR(255),
                github VARCHAR(255),
                twitter VARCHAR(255),
                portfolio VARCHAR(255),
                username VARCHAR(255),
                profile_image VARCHAR(255),
                description TEXT,
                PRIMARY KEY (email)
            );";
            $this->query($table);

            $links = "CREATE TABLE IF NOT EXISTS user_links (
                user_id VARCHAR(255),
                url VARCHAR(255),
                title VARCHAR(255),
                FOREIGN KEY (user_id) REFERENCES users(email)
            );";
            $this->query($links);
            
        } catch (Exception $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function select($table, $where = null) {
        $sql = "SELECT * FROM $table";
        if ($where) {
            $sql .= " WHERE " . $this->buildWhereClause($where);
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($table, $data) {
        $keys = implode(',', array_keys($data));
        $values = "'" . implode("','", array_values($data)) . "'";
        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        return $this->query($sql);
    }

    public function update($table, $data, $where) {
        $setClause = '';
        foreach ($data as $key => $value) {
            $setClause .= "$key='$value',";
        }
        $setClause = rtrim($setClause, ',');
        $sql = "UPDATE $table SET $setClause WHERE " . $this->buildWhereClause($where);
        return $this->query($sql);
    }

    public function delete($table, $where) {
        $sql = "DELETE FROM $table WHERE " . $this->buildWhereClause($where);
        return $this->query($sql);
    }

    private function buildWhereClause($where) {
        $conditions = [];
        foreach ($where as $key => $value) {
            $conditions[] = "$key='$value'";
        }
        return implode(' AND ', $conditions);
    }

    public function close() {
        $this->conn = null;
    }
}
