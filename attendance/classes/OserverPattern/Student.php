<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17/04/2018
 * Time: 05:05 ص
 */

require_once ('Person.php');

class Student extends Person implements Personprocess
{

    public function __construct($id,$name)
    {
        parent::__construct($id, $name);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function setMessage($From, $to, $messageContet)
    {
        $Instructor__id=$From;
        $Student_id=$to;
        $messageContent=$messageContet;

        /*
         Sent Message to Instructor Code
          */
    }
}