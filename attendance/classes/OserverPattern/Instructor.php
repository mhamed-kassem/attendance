<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17/04/2018
 * Time: 05:06 ص
 */




require_once ('Personprocess.php');
class Instructor implements Personprocess
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


    public function setMessage($From, $to, $messageContet)
    {
       $Instructor_id=$From;
       $Student_id=$to;
       $messageContent=$messageContet;
       $this->db_transection->InsertNotification($Instructor_id,$Student_id,$messageContent);
    }
}
