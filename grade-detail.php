<?php
require_once "../config.php";
\Tsugi\Core\LTIX::getConnection();

use \Tsugi\Util\U;
use \Tsugi\Core\Annotate;
use \Tsugi\Grades\GradeUtil;

session_start();

$user_id = U::safe_href(U::get($_REQUEST, 'user_id'));
if ( ! $user_id ) {
    die('user_id is required');
}

// Get the user's grade data also checks session
// and sets $LAUNCH
$row = GradeUtil::gradeLoad($user_id);

$annotations = Annotate::loadAnnotations($LAUNCH, $user_id);

// View
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->flashMessages();

// Show the basic info for this user
GradeUtil::gradeShowInfo($row);

if ( $annotations ) {
    $next = "grade-detail.php?user_id=".$user_id;
    echo('<p><a href="index.php?user_id='.$user_id.'&next='.urlencode($next).'">');
    echo(__('Annotate'));
    echo("</a><p>\n");
}


$OUTPUT->footer();
