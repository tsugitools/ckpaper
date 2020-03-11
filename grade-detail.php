<?php
require_once "../config.php";
\Tsugi\Core\LTIX::getConnection();

use \Tsugi\Util\U;
use \Tsugi\UI\Table;
use \Tsugi\Core\Annotate;
use \Tsugi\Core\Result;
use \Tsugi\Core\LTIX;
use \Tsugi\Grades\GradeUtil;

session_start();

$user_id = U::safe_href(U::get($_REQUEST, 'user_id'));
if ( ! $user_id ) {
    die('user_id is required');
}

// Set up the GET Params that we want to carry around.
$getparms = $_GET;
unset($getparms['delete']);
unset($getparms['resend']);

$self_url = addSession('grade.php?user_id='.$user_id);

// Get the user's grade data also checks session
// and sets $LAUNCH
$row = GradeUtil::gradeLoad($user_id);

$annotations = Annotate::loadAnnotations($LAUNCH, $user_id);

$inst_note = $LAUNCH->result->getJsonKeyForUser('inst_note', '', $user_id);
$gradeurl = Table::makeUrl('grade-detail.php', $getparms);
$gradesurl = Table::makeUrl('grades.php', $getparms);

// Handle incoming post to set the instructor points and update the grade
if ( isset($_POST['instSubmit']) || isset($_POST['instSubmitAdvance']) ) {

    $inst_note = U::get($_POST, 'inst_note');
    $LAUNCH->result->setJsonKeyForUser('inst_note', U::get($_POST, 'inst_note'), $user_id );

    $points = U::get($_POST, 'grade');
    if ( strlen($points) == 0 || $points === null ) {
        $points = null;
    } else if ( is_numeric($points) ) {
        $points = $points + 0;
    } else {
        $_SESSION['error'] = "Points must either by a number or blank.";
        header( 'Location: '.addSession($gradeurl) ) ;
        return;
    }
    $computed_grade = $points / 100.0;

    $result = Result::lookupResultBypass($user_id);
    $result['grade'] = -1; // Force resend
    $debug_log = array();
    $status = LTIX::gradeSend($computed_grade, $result, $debug_log); // This is the slow bit
    if ( $status === true ) {
        $_SESSION['success'] = 'Grade submitted to server';
    } else {
        error_log("Problem sending grade ".$status);
        $_SESSION['error'] = 'Error sending grade to: '.$status;
        $_SESSION['debug_log'] = $debug_log;
    }
    header( 'Location: '.addSession($gradeurl) ) ;
    return;
}


$menu = new \Tsugi\UI\MenuSet();
$menu->addLeft('Back to all grades', $gradesurl);

// View
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav($menu);
$OUTPUT->flashMessages();

// Show the basic info for this user
GradeUtil::gradeShowInfo($row, false);

if ( $annotations ) {
    $next = "grade-detail.php?user_id=".$user_id;
    echo('<p><a href="index.php?user_id='.$user_id.'&next='.urlencode($next).'">');
    echo(__('View / Annotate Submission'));
    echo("</a><p>\n");
}

$next_user_id_ungraded = false;

$inst_note = $LAUNCH->result->getJsonKeyForUser('inst_note', $user_id);

echo('<form method="post">
      <input type="hidden" name="user_id" value="'.$user_id.'">');

if ( $next_user_id_ungraded !== false ) {
      echo('<input type="hidden" name="next_user_id_ungraded" value="'.$next_user_id_ungraded.'">');
}

echo('<label for="percent">Percentage (0-100)</label>
      <input type="number" name="percent" id="grade" min="0" max="100"/><br/>');

echo('<label for="inst_note">Instructor Note To Student</label><br/>
      <textarea name="inst_note" id="inst_note" style="width:60%" rows="5">');
echo(htmlentities($inst_note));
echo('</textarea><br/>
      <input type="submit" name="instSubmit" value="Update" class="btn btn-primary">');

if ( $next_user_id_ungraded !== false ) {
    echo(' <input type="submit" name="instSubmitAdvance" value="Update and Go To Next Ungraded Student" class="btn btn-primary">');
}
echo('</form>');




$OUTPUT->footer();
