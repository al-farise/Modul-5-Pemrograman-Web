<?php

namespace app\Models;

include "app/Config/DatabaseConfig.php";

use app\Config\DatabaseConfig;
use mysqli;

class Product extends DatabaseConfig
{
    public $connection;

    public function __construct()
    {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database_name, $this->port);

        if ($this->connection->connect_error)
        {
            die("Connection failed: " . $this->connection->connect_errno);
        }
    }

    public function findAll()
    {
        $sql = "SELECT * FROM products";
        $result = $this->connection->query($sql);
        $this->connection->close();

        $data = [];

        while($row = $result->fetch_assoc())
        {
            $data[] = $row;
        }

        return $data;
    }

    public function findById($id)
    {

        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $this->connection->close();
        $data = [];
        while ($row = $result->fetch_assoc())
        {
            $data[] = $row;
        }
        return $data;
    }

    public function create($data)
    {
        $productName = $data['product_name'];
        $query = "INSERT INTO products (product_name) VALUES (?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $productName);
        $stmt->execute();
        $this->connection->close();
    }

    public function update($data, $id)
    {
        $productName = $data['product_name'];
        $query = "UPDATE products SET product_name = ? WHERE id = ? ";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("si", $productName, $id);
        $stmt->execute();
        $this->connection->close();
    }

    public function destroy($id)
    {
        $query = "DELETE FROM products WHERE id = ? ";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $this->connection->close();
    }
}
