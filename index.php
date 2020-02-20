<?php
require_once "../config.php";

// The Tsugi PHP API Documentation is available at:
// http://do1.dr-chuck.com/tsugi/phpdoc/

use \Tsugi\Util\U;
use \Tsugi\Util\Net;
use \Tsugi\Core\LTIX;
use \Tsugi\Core\Settings;
use \Tsugi\UI\SettingsForm;

// No parameter means we require CONTEXT, USER, and LINK
$LAUNCH = LTIX::requireData();

// If settings were updated
if ( SettingsForm::handleSettingsPost() ) {
    header( 'Location: '.addSession('index.php') ) ;
    return;
}

// Handle Post Data
$p = $CFG->dbprefix;
$old_content = $LAUNCH->link->getJsonKey('content', '');

if ( U::get($_POST, 'content') ) {
    $LAUNCH->link->setJsonKey('content', U::get($_POST, 'content') );
    $PDOX->queryDie("DELETE FROM {$p}attend WHERE link_id = :LI",
            array(':LI' => $LINK->id)
    );
    $_SESSION['success'] = 'Updated';
    header( 'Location: '.addSession('index.php') ) ;
    return;
} 

// Render view
$OUTPUT->header();
?>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
<?php
$OUTPUT->bodyStart();
$OUTPUT->topNav();

if ( $USER->instructor ) {
    echo('<div style="float:right;">');
    echo('<form method="post" style="display: inline">');
    echo('<input type="submit" class="btn btn-warning" name="clear" value="'.__('Clear data').'">');
    echo("</form>\n");
    SettingsForm::button(false);
    echo('</div>');

    $OUTPUT->welcomeUserCourse();
    echo('<br clear="all">');
    SettingsForm::start();
    echo("<p>Configure the LTI Tool<p>\n");
    SettingsForm::text('code',__('Code'));
    SettingsForm::checkbox('grade',__('Send a grade'));
    SettingsForm::text('match',__('This can be a prefix of an IP address like "142.16.41" or if it starts with a "/" it can be a regular expression (PHP syntax)'));
    echo("<p>Your current IP address is ".htmlentities(Net::getIP())."</p>\n");
    SettingsForm::done();
    SettingsForm::end();
}

$OUTPUT->flashMessages();

echo("<!-- Classic single-file version of the tool -->\n");

?>
    <form method="post">
        <textarea name="content" id="editor">
<?= $old_content ?>
        </textarea>
        <p><input type="submit" value="Submit"></p>
    </form>
    <script>
        var editor = ClassicEditor
            .create( document.querySelector( '#editor' ) ,
                {
                }
            ).then(editor => { 
                editor.isReadOnly = true;
            } ).catch( error => {
                console.error( error );
            } );
    </script>
<?php
$OUTPUT->footer();
