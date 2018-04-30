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



    public function InsertNotification($uset_idFrom,$user_idTo,$mossageContent,$status){
        $subject=null;
        if($status == 0)
        {
            $subject = 'Warnning';
        }else{
            $subject = 'Excuse From Student';
        }
            $sql = "INSERT INTO `mdl_notifications`(`useridfrom`, `useridto`, `subject`, `fullmessage`, `fullmessageformat`, `fullmessagehtml`, `smallmessage`, `timeread`, `timecreated`)
                       VALUES ($uset_idFrom,$user_idTo,'$subject','$mossageContent',0,'<p>$mossageContent</p>','$mossageContent',1523849753,1523849753)";

            if (mysqli_query($this->con, $sql)) {
                if($status == 0)
                {
                    $this->UpdateNotification($user_idTo);
                }
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



    public function IsStudent($user_id)
    {
        $query = "SELECT DISTINCT studentid FROM `mdl_attendance_log` WHERE studentid = $user_id ";
        $student=NULL;
        if ($result = mysqli_query($this->con, $query)) {
            // Fetch one and one row
            while ($row = mysqli_fetch_row($result)) {
                return $row[0];
            }
            // Free result set
            mysqli_free_result($result);
        }
        return $student;
    }

    public function IsTeacher($user_id)
    {
        $query = "SELECT DISTINCT takenby FROM `mdl_attendance_log` WHERE takenby = $user_id ";
        $teacher=NULL;
        if ($result = mysqli_query($this->con, $query)) {
            // Fetch one and one row
            while ($row = mysqli_fetch_row($result)) {
                return $row[0];
            }
            // Free result set
            mysqli_free_result($result);
        }
        return $teacher;
    }

    public function get_teachernameby_ID($teacher_id)
    {
        $query = "SELECT username FROM `mdl_user` WHERE id = $teacher_id ";
        $techar_name=NULL;
        if ($result = mysqli_query($this->con, $query)) {
            // Fetch one and one row
            while ($row = mysqli_fetch_row($result)) {
                return $row[0];
            }
            // Free result set
            mysqli_free_result($result);
        }
        return $techar_name;
    }

    public function Teachers_names()
    {
        $query = "SELECT DISTINCT takenby FROM `mdl_attendance_log` ";
        $teacher=NULL;
        if ($result = mysqli_query($this->con, $query)) {
            // Fetch one and one row
            $i=0;
            $get_name=NULL;
            while ($row = mysqli_fetch_row($result)) {
                $get_name = $this->get_teachernameby_ID($row[0]);
                if($get_name != NULL)
                {
                    $teacher[$i][0] = $row[0]; // teacher_id
                    $teacher[$i][1] = $get_name; //teacher_name
                        $i++;
                }
            }
            // Free result set
            mysqli_free_result($result);
        }
        return $teacher;
    }

    public function Courses_name()
    {
        $query = "SELECT fullname FROM `mdl_course`";
        $course=NULL;
        if ($result = mysqli_query($this->con, $query)) {
            // Fetch one and one row
            $i=0;
            while ($row = mysqli_fetch_row($result)) {
                $course[$i] = $row[0];
                $i++;
            }
            // Free result set
            mysqli_free_result($result);
        }
        return $course;
    }


    public function AcceptExcuse($Student_id,$teacher_id,$finally)
    {
        if($finally == 0)
        {
            $subject ='Accept ->:  Excuse From Student';
        }else {
            $subject ='Accept ---->:  Excuse From Student';
        }
        $sql = "UPDATE `mdl_notifications` SET subject ='$subject' WHERE useridfrom =$Student_id AND useridto = $teacher_id AND subject = 'Excuse From Student' ORDER BY id DESC LIMIT 1 ";

        if (mysqli_query($this->con, $sql)) {
            return true;
        } else {
            echo $sql;
            return false;
        }
    }

    public function RejectExcuse($Student_id,$teacher_id,$finally)
    {
        if($finally == 0)
        {
            $subject ='Reject ->:  Excuse From Student';
        }else{
            $subject ='Reject ---->:  Excuse From Student';
        }
        $sql = "UPDATE `mdl_notifications` SET subject ='$subject' WHERE useridfrom =$Student_id AND useridto = $teacher_id AND subject = 'Excuse From Student' ORDER BY id DESC LIMIT 1";

        if (mysqli_query($this->con, $sql)) {
            return true;
        } else {
            echo $sql;
            return false;
        }
    }


}