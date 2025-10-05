<?php

class Repository
{
    private $conn;

    function __construct($servername, $username, $password, $dbname)
    {
        $this->conn = new mysqli($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
        
    }

    function getAll(string $table, array $selector = []) : array
    {
        $sql = "SELECT * FROM $table";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    function getByCondition(string $table, array $conditions = []) : array
    {
        $condition_string = "";
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $condition_string = $condition_string . "$key='$value'";
            }

            $sql = "SELECT * FROM $table WHERE " . $condition_string;
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC);
            } else {
                return [];
            }
        }
        else {
            return $this->getAll($table);
        }
    }

    function insert(string $table, array $data) : bool
    {
        $columns = implode(", ", array_keys($data));
        $values  = implode("', '", array_values($data));
        $sql = "INSERT INTO $table ($columns) VALUES ('$values')";
        return $this->conn->query($sql) === TRUE;
    }
}