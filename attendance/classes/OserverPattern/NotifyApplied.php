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
    function __construct($From,$to)
    {
        $this->messagedetail = array();
        $this->From = $From;
        $this->to = $to;

    }

    public function AddMessage(Personprocess $messagedetail)
    {
        array_push($this->messagedetail,$messagedetail);
    }

    public function notify()
    {
        foreach ($this->messagedetail as $Mdetails){
            $Mdetails->setMessage($this->From,$this->to,$Mdetails->getMessageContet());
        }
    }
}

