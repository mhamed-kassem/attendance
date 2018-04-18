<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17/04/2018
 * Time: 08:30 ص
 */


class db_Transactions
{

    private $con=null;
    function __construct()
    {
      $this->connect();
    }

    private function connect()
    {
        $this->con = mysqli_connect('localhost', 'root','', 'db_moodle');
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

    public function getall_stundents()
    {
        $query = "SELECT DISTINCT studentid FROM `mdl_attendance_log` ";
        $student=NULL;
        if ($result = mysqli_query($this->con, $query)) {
            // Fetch one and one row
            $i=0;
            while ($row = mysqli_fetch_row($result)) {
                $student[$i] = $row[0];
                $i++;
            }
            // Free result set
            mysqli_free_result($result);
        }
        return $student;
    }

    public function check_AsbenseStudent($status_id)
    {
        $query = "SELECT acronym FROM `mdl_attendance_statuses` WHERE id = $status_id ";
        if ($result = mysqli_query($this->con, $query)) {
            // Fetch one and one row
            while ($row = mysqli_fetch_row($result)) {
                return $row[0];
            }
            // Free result set
            mysqli_free_result($result);
        }
        return null;
    }


    public function checkStudent_AsbenseTwo($student_id)
    {
        $check_valye=null;
        $studentwillSentWarning=null;
        $count=0;
                $query = "SELECT statusid,studentid,takenby FROM `mdl_attendance_log` where remarks = '1' AND studentid = $student_id";
                if ($result = mysqli_query($this->con, $query)) {
                    // Fetch one and one row
                    while ($row = mysqli_fetch_row($result)) {
                        $check_valye =  $this->check_AsbenseStudent($row[0]);

                        if($check_valye == 'A')
                        {
                           $count = $count + 1;
                           if($count == 2)
                           {
                                   $count = 0;
                                   $studentwillSentWarning[0][0]=$row[1];
                                   $studentwillSentWarning[0][1]=$row[2];
                           }

                        }

                    }
                    // Free result set
                    mysqli_free_result($result);
                }
        return $studentwillSentWarning;
    }



    public function InsertNotification($uset_idFrom,$user_idTo,$mossageContent){
            $sql = "INSERT INTO `mdl_notifications`(`useridfrom`, `useridto`, `subject`, `fullmessage`, `fullmessageformat`, `fullmessagehtml`, `smallmessage`, `timeread`, `timecreated`)
                       VALUES ($uset_idFrom,$user_idTo,'Warnning','$mossageContent',0,'<p>$mossageContent</p>','$mossageContent',1523849753,1523849753)";

            if (mysqli_query($this->con, $sql)) {
                $this->UpdateNotification($user_idTo);
                return true;
            } else {
                return false;
            }
    }

    public function UpdateNotification($Student_id)
    {
        $sql = "UPDATE `mdl_attendance_log` SET remarks ='0' WHERE studentid =$Student_id ";

        if (mysqli_query($this->con, $sql)) {
            return true;
        } else {
            echo $sql;
            return false;
        }
    }


}