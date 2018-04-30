<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * View a user's notifications.
 *
 * @package    message_popup
 * @copyright  2016 Ryan Wyllie <ryan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

$notificationid = optional_param('notificationid', 0, PARAM_INT);
$offset = optional_param('offset', 0, PARAM_INT);
$limit = optional_param('limit', 0, PARAM_INT);
$userid = $USER->id;

$url = new moodle_url('/message/output/popup/notifications.php');
$url->param('id', $notificationid);

$PAGE->set_url($url);

require_login();

if (isguestuser()) {
    print_error('guestnoeditmessage', 'message');
}

if (!$user = $DB->get_record('user', ['id' => $userid])) {
    print_error('invaliduserid');
}

$personalcontext = context_user::instance($user->id);

$PAGE->set_context($personalcontext);
$PAGE->set_pagelayout('admin');

// Display page header.
$title = get_string('notifications', 'message');
$PAGE->set_title("{$SITE->shortname}: " . $title);
$PAGE->set_heading(fullname($user));

// Grab the renderer.
$renderer = $PAGE->get_renderer('core', 'message');
$context = [
    'notificationid' => $notificationid,
    'userid' => $userid,
    'limit' => $limit,
    'offset' => $offset,
];

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('notifications', 'message'));
echo $renderer->render_from_template('message_popup/notification_area', $context);
require_once ('../../../mod/attendance/classes/db_Connect/db_Transactions.php');
$transaction_Db=new db_Transactions();
$isstudent=$transaction_Db->IsStudent($user->id);
if($isstudent != NULL) {
   $teacher_detail= $transaction_Db->Teachers_names();
   $courses = $transaction_Db->Courses_name()
?>
<div class="row">
   <div class="col-md-6 col-lg-6">
    <form action="notifications.php"  class="form-group" method="post">
        <label>Teacher name:</label>
        <select name="teachar_id" class="form-control" required>
            <?php for($i=0;$i < count($teacher_detail);$i++) {?>
            <option value="<?php echo  $teacher_detail[$i][0];?>"><?php echo  $teacher_detail[$i][1];?></option>
            <?php }?>
        </select>
        <label>Course name:</label>
        <select name="course_name" class="form-control" required>
            <?php for($i=0;$i < count($courses);$i++) {?>
                <option value="<?php echo  $courses[$i];?>"><?php echo  $courses[$i];?></option>
            <?php }?>
        </select>

        <label>Your Excuse message:</label>
        <textarea cols="50" rows="8" style="color: red" name="message" class="form-control" required>
 "Write your Excuse Here!"
        </textarea>
        <input type="submit" class="btn btn-danger" name="submit" value="Sent Excuse">
    </form>
   </div>
<?php
}

if(isset($_POST['submit']) && $_POST['submit'] == "Sent Excuse")
{

    $teacher_id = $_POST['teachar_id']; // sent excuse to teacher
    $student_id = $user->id; //sent excuse From Teacher
    $course_name=$_POST['course_name'];
    $messageContent="Course name is:".$course_name." /   Student_id :".$student_id;
    $messageContent.='/ message Content :  '.$_POST['message'];


    require_once ('../../../mod/attendance/classes/OserverPattern/Student.php');
    require_once ('../../../mod/attendance/classes/OserverPattern/NotifyApplied.php');

    $Student=new Student($messageContent,$transaction_Db);
    $notify=new NotifyApplied($student_id,$teacher_id,1);
    $notify->AddMessage($Student);
    $notify->notify();

}
?>
<?php
$is_Teacher = $transaction_Db->IsTeacher($user->id);

if($is_Teacher != NULL)
{
?>
  <div class="col-md-6 col-lg-6">
    <form action="notifications.php" class="form-group" method="post">
        <label>Student Id :</label>
        <input type="number" style="width: 100px" name="Student_id" class="form-control" required>
        <br>
        <input type="submit" class="btn btn-danger" name="submit" value="Accept">
        <input type="submit" class="btn btn-danger" name="submit" value="Reject">
    </form>
   </div>
</div>
<?php
}
?>
<?php
if(isset($_POST['submit']) && $_POST['submit'] == "Accept")
{
    $student_id = $_POST['Student_id'];
    $transaction_Db->AcceptExcuse($student_id,$user->id,0);
    $teacher_name=$user->username;
    $messageContent="Instructor :".$teacher_name." / Accept --------> your Excuse";

    require_once ('../../../mod/attendance/classes/OserverPattern/Instructor.php');
    require_once ('../../../mod/attendance/classes/OserverPattern/NotifyApplied.php');

    $instructor=new Instructor($messageContent,$transaction_Db);
    $notify=new NotifyApplied($user->id,$student_id,1);
    $notify->AddMessage($instructor);
    $notify->notify();

    $transaction_Db->AcceptExcuse($student_id,$user->id,1);
}


if(isset($_POST['submit']) && $_POST['submit'] == "Reject")
{
    $student_id = $_POST['Student_id'];
    //$transaction_Db->RejectExcuse($student_id,$user->id,0);
    $teacher_name=$user->username;
    $messageContent="Instructor :".$teacher_name."  /  Reject ---------> your Excuse";

    require_once ('../../../mod/attendance/classes/OserverPattern/Instructor.php');
    require_once ('../../../mod/attendance/classes/OserverPattern/NotifyApplied.php');

    $instructor=new Instructor($messageContent,$transaction_Db);
    $notify=new NotifyApplied($user->id,$student_id,1);
    $notify->AddMessage($instructor);
    $notify->notify();

    $transaction_Db->RejectExcuse($student_id,$user->id,1);
}

echo $OUTPUT->footer();
?>