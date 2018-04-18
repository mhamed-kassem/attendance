<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17/04/2018
 * Time: 08:23 ص
 */
class connect_dataB
{
    protected $cxn;

    function connectToDb()
    {
        include 'db_connect.php';
        $vars = "var.php";
        $this->cxn = new db_connect($vars);
    }

    public function close()
    {
        $this->cxn->close();
    }
}