<?php

class Repository
{
    private $conn;
    public $lastError;

    function __construct($servername, $username, $password, $dbname)
    {
        $this->conn = new mysqli($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
        
    }

    private function getSelectorString(array $selector) : string
    {
        if ($selector == []) {
            return "*";
        }

        $ret_str = "";
        foreach ($selector as $value) {
            $ret_str = $ret_str . $value . ', ';
        }
        $ret_str = rtrim($ret_str, ", ");
        return $ret_str;
    }

    private function tryQuery(string $sql) : ?mysqli_result
    {
        try {
            $result = $this->conn->query($sql);
            return $result;
        } catch (Exception $e) {
            $this->lastError = $e;
            return null;
        }
    }

    function getAll(string $table, array $selector = []) : array
    {
        $sql = "SELECT " . $this->getSelectorString($selector) . " FROM $table";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    function getByRawCondition(string $table, array $selector = [], string $condition) : array
    {
        $sql = "SELECT " . $this->getSelectorString($selector) . " FROM $table WHERE $condition";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    function getByCondition(string $table, array $selector = [], array $conditions = []) : array
    {
        $condition_string = "";
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $condition_string = $condition_string . "$key='$value'";
            }

            $sql = "SELECT " . $this->getSelectorString($selector) . " FROM $table WHERE " . $condition_string;
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

    function updateId(string $table, int $id, array $updated_data) : bool
    {
        $update_string = "";
        foreach ($updated_data as $key => $value) {
            $update_string = $update_string . "$key='$value',";
        }
        rtrim($update_string, ",");
        $sql = "UPDATE $table SET $update_string WHERE ID=$id";
        return $this->conn->query($sql) === TRUE;
    }

    function exists(string $table, array $conditions) : bool
    {
        if ($this->getByCondition($table, [], $conditions) != [])
        {
            return true;
        }
        
        return false;
    }

    function getOneById(string $table, array $selector, int $id) : array
    {
        $sql = "SELECT " . $this->getSelectorString($selector) . " FROM $table WHERE ID=$id";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return [];
        }
    }

    function deleteById(string $table, int $id) : bool
    {
        $sql = "DELETE FROM $table WHERE ID=$id";
        return $this->conn->query($sql) === TRUE;
    }

    function rawSql(string $query) : array
    {
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
}