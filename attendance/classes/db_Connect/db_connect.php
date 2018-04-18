<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17/04/2018
 * Time: 08:17 ص
 */
class db_connect
{
    private $host;
    private $user;
    private $password;
    private $database;
    private $con;

    function __construct($filename)
    {
        if(is_file($filename)) include $filename;
        else throw new Exception("Error!");

        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;

        $this->connect();
    }

    private function connect()
    {
        $this->con = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        // Check connection
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQLi: " . mysqli_connect_error();
        }
        mysqli_query($this->con,"SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
    }


    public function close()
    {
        mysqli_close($this->con);
    }
}