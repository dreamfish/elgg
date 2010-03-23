<?php
// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
?>
<script>
 jQuery.noConflict();
 var jquery_hack = $;
 var $ = jQuery;
</script>
<?php
echo elgg_view('page_elements/elgg_topbar');
?>
<script>
 $ = jquery_hack;
</script>