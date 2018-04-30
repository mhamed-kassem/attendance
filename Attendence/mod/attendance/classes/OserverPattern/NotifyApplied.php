<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17/04/2018
 * Time: 09:48 ص
 */
require_once ('Observer.php');

class NotifyApplied implements Observer
{
    private $messagedetail;
    private $From;
    private $to;
    private $status;
    function __construct($From,$to,$status)
    {
        $this->messagedetail = array();
        $this->From = $From;
        $this->to = $to;
        $this->status = $status;

    }

    public function AddMessage(Personprocess $messagedetail)
    {
        array_push($this->messagedetail,$messagedetail);
    }

    public function notify()
    {
        foreach ($this->messagedetail as $Mdetails){
            $Mdetails->setMessage($this->From,$this->to,$Mdetails->getMessageContet(),$this->status);
        }
    }
}

