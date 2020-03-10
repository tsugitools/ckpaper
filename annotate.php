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

// Render view
$OUTPUT->header();
?>
<link rel="stylesheet" href="<?= $CFG->staticroot ?>/js/annotator-full.1.2.10/annotator.min.css" />
<?php
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
// https://github.com/jitbit/HtmlSanitizer
$pieces = U::rest_path();
// $api_endpoint = $pieces->parent . '/store/' . session_id();
$api_endpoint = $CFG->wwwroot . '/api/annotate/' . session_id();
?>
<script src="https://cdn.jsdelivr.net/gh/jitbit/HtmlSanitizer@master/HtmlSanitizer.js"></script>
<script src="<?= $CFG->staticroot ?>/js/annotator-full.1.2.10/annotator-full.min.js"></script>
<script type="text/javascript">
$(document).ready( function () {
    $.get('<?= addSession('load_text.php') ?>', function(data) {
      var html = HtmlSanitizer.SanitizeHtml(data);
      $('#output_div').html(html);
      $('#spinner').hide();
      $('#output_div').show();
      console.log('new app...');
      // Annotator.Plugin.Store.prefix = '<?= $api_endpoint ?>';
      console.log(Annotator);
      // Annotator.Plugin.Store.prototype.options['prefix'] = '<?= $api_endpoint ?>';
      // Annotator.Plugin.Auth = false;

      $('#output_div').annotator()
      .annotator('setupPlugins', {} , {
         Auth: false,
         Tags: false,
         Filter: false,
         Store: {
            prefix: '<?= $api_endpoint ?>',
            loadFromSearch: false
         }
      } );
      console.log('Annotator started');
    })
  }
);
</script>
<?php
$OUTPUT->footerEnd();
