<?php
require_once "../config.php";

// The Tsugi PHP API Documentation is available at:
// http://do1.dr-chuck.com/tsugi/phpdoc/

use \Tsugi\Util\U;
use \Tsugi\Core\LTIX;
use \Tsugi\UI\Annotate;

// No parameter means we require CONTEXT, USER, and LINK
$LAUNCH = LTIX::requireData();

$next = U::safe_href(U::get($_GET, 'next', 'index.php'));
$user_id = U::safe_href(U::get($_GET, 'user_id'));
if ( $user_id && ! $LAUNCH->user->instructor ) {
    http_response_code(404);
    die('Not authorized');
}
if ( ! $user_id ) $user_id = $LAUNCH->user->id;

$back_text = __('Back');
$load_url = $user_id ? 'load_text.php?user_id=' . $user_id : 'load_text.php';

$menu = new \Tsugi\UI\MenuSet();
$menu->addLeft($back_text, $next);

// Render view
$OUTPUT->header();
echo(Annotate::header());
$OUTPUT->bodyStart();
$OUTPUT->topNav($menu);
$OUTPUT->flashMessages();

?>
    <div id="spinner"><img src="<?= $OUTPUT->getSpinnerUrl() ?>"/></div>
    <div id="output_div" style="display: none;">
    </div>
<?php
$OUTPUT->footerStart();
echo(Annotate::footer($user_id));
// https://github.com/jitbit/HtmlSanitizer
?>
<script src="https://cdn.jsdelivr.net/gh/jitbit/HtmlSanitizer@master/HtmlSanitizer.js"></script>
<script type="text/javascript">
$(document).ready( function () {
    $.get('<?= addSession($load_url) ?>', function(data) {
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
