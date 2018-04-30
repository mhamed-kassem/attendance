<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17/04/2018
 * Time: 05:05 ص
 */

require_once ('Personprocess.php');

class Student  implements Personprocess
{

    protected $messageContet;
    protected $db_transection;
    /**
     * Instructor constructor.
     */
    public function __construct($messageContet,db_Transactions $db_transection)
    {
        $this->db_transection=$db_transection;
        $this->messageContet=$messageContet;

    }

    /**
     * @return mixed
     */
    public function getMessageContet()
    {
        return $this->messageContet;
    }

    /**
     * @param mixed $messageContet
     */
    public function setMessageContet($messageContet)
    {
        $this->messageContet = $messageContet;
    }


    public function setMessage($From, $to, $messageContet,$Status)
    {
        $Student_id=$From;
        $Instructor_id=$to;
        $messageContent=$messageContet;
        $this->db_transection->InsertNotification($Student_id,$Instructor_id,$messageContent,$Status);
    }
}