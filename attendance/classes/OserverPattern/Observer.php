<?php


interface Observer{

    public function AddMessage(Personprocess $messagedetail);
    public function notify();
}

