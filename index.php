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
$old_content = $LAUNCH->result->getJsonKey('content', '');

if ( U::get($_POST, 'content') ) {
    $LAUNCH->result->setJsonKey('content', U::get($_POST, 'content') );
    $PDOX->queryDie("DELETE FROM {$p}attend WHERE link_id = :LI",
            array(':LI' => $LINK->id)
    );
    $_SESSION['success'] = 'Updated';
    header( 'Location: '.addSession('index.php') ) ;
    return;
} 

// Render view
$OUTPUT->header();
// https://github.com/jitbit/HtmlSanitizer

$OUTPUT->bodyStart();
$OUTPUT->topNav();

if ( $USER->instructor ) {
    echo('<div style="float:right;">');
    echo('<a href="annotate.php" class="btn btn-primary">Annotate</a>');
    SettingsForm::button(false);
    echo('</div>');

    $OUTPUT->welcomeUserCourse();
    echo('<br clear="all">');
    SettingsForm::start();
    echo("<p>Configure the LTI Tool<p>\n");
    SettingsForm::text('code',__('Code'));
    SettingsForm::checkbox('grade',__('Send a grade'));
    SettingsForm::done();
    SettingsForm::end();
}

$OUTPUT->flashMessages();

echo("<!-- Classic single-file version of the tool -->\n");

?>
    <div id="spinner"><img src="<?= $OUTPUT->getSpinnerUrl() ?>"/></div>
    <div id="editor_div" style="display: none;">
    <form method="post">
        <textarea name="content" id="editor">
        </textarea>
        <p><input type="submit" value="Submit"></p>
    </form>
    </div>
<?php
$OUTPUT->footerStart();
?>
<script src="https://cdn.jsdelivr.net/gh/jitbit/HtmlSanitizer@master/HtmlSanitizer.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
<script type="text/javascript">
ClassicEditor.defaultConfig = {
    toolbar: {
        items: [
            'heading',
            '|',
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            // 'imageUpload',
            'blockQuote',
            'insertTable',
            'mediaEmbed',
            'undo',
            'redo'
        ]
    },
    
}

$(document).ready( function () {
    $.get('<?= addSession('load_text.php') ?>', function(data) {
      console.log(data);
      var html = HtmlSanitizer.SanitizeHtml(data);
      console.log(html);
      $('#editor').html(html);
      $('#output_div').html(html);
      ClassicEditor
            .create( document.querySelector( '#editor' ) ,
                {
                }
            ).then(editor => { 
                // editor.isReadOnly = true;
                $('#spinner').hide();
                $('#editor_div').show();
            } ).catch( error => {
                console.error( error );
            } );
    })
  }
);
</script>
<?php
$OUTPUT->footerEnd();

