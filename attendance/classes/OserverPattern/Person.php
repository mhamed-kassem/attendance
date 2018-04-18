<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17/04/2018
 * Time: 05:04 ص
 */
Abstract class Person
{
 protected $id;
 protected $name;

 function __construct($id,$name)
 {
     $this->id=$id;
     $this->name=$name;
 }

}