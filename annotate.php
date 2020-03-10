<?php
require_once "../config.php";

// The Tsugi PHP API Documentation is available at:
// http://do1.dr-chuck.com/tsugi/phpdoc/

use \Tsugi\Util\U;
use \Tsugi\Core\LTIX;
use \Tsugi\UI\Annotate;

// No parameter means we require CONTEXT, USER, and LINK
$LAUNCH = LTIX::requireData();

// Render view
$OUTPUT->header();
echo(Annotate::header());
$OUTPUT->bodyStart();
$OUTPUT->topNav();
$OUTPUT->flashMessages();
?>
<button onclick="window.location.href = '<?= addSession('index.php') ?>';"
style="position: fixed; border-radius: 4px; border: 4px solid darkblue; z-index: 10000; color: white; background-color: #008CBA;
 font-size: 16px; right:20px;  padding: 10px 16px;">Edit</button>

    <div id="spinner"><img src="<?= $OUTPUT->getSpinnerUrl() ?>"/></div>
    <div id="output_div" style="display: none;">
    </div>
<?php
$OUTPUT->footerStart();
echo(Annotate::footer($LAUNCH));
// https://github.com/jitbit/HtmlSanitizer
?>
<script src="https://cdn.jsdelivr.net/gh/jitbit/HtmlSanitizer@master/HtmlSanitizer.js"></script>
<script type="text/javascript">
$(document).ready( function () {
    $.get('<?= addSession('load_text.php') ?>', function(data) {
      var html = HtmlSanitizer.SanitizeHtml(data);
      $('#output_div').html(html);
      $('#spinner').hide();
      $('#output_div').show();
      tsugiStartAnnotation('#output_div');
    })
  }
);
</script>
<?php
$OUTPUT->footerEnd();
