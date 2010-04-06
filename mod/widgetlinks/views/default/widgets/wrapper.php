<?php

	/**
	 * Elgg widget wrapper
	 * @package widgetlinks
	 * @author Curverider Ltd, modified by Adolfo Mazorra
	 * @link http://elgg.org/
	 */

	static $widgettypes;
	
	$callback = get_input('callback');
	
	if (!isset($widgettypes)) $widgettypes = get_widget_types();
	
	if ($vars['entity'] instanceof ElggObject && $vars['entity']->getSubtype() == 'widget') {
		$handler = $vars['entity']->handler;
		$title = $widgettypes[$handler]->name;
		if (isset($widgettypes[$handler]->link)) {
			$link = $widgettypes[$handler]->link;
			/* Let's do some basic substitutions to the link */
			$page_owner = page_owner_entity();
			if ($page_owner === false || is_null($page_owner)) {
							$page_owner = $_SESSION['user'];
			}
			$page_owner_username = page_owner_entity()->username;
			/* [USERNAME] */
			$link = preg_replace('#\[USERNAME\]#', $page_owner_username, $link);
			/* [BASEURL] */
			$link = preg_replace('#\[BASEURL\]#', $vars['url'], $link);
		}		
		if (!$title)
			$title = $handler;
	} else {
		$handler = "error";
		$title = elgg_echo("error"); 
	}
	
	if ($callback != "true") {
	
?>

	<div id="widget<?php echo $vars['entity']->getGUID(); ?>">
	<div class="collapsable_box">
	<div class="collapsable_box_header">
	<a href="javascript:void(0);" class="toggle_box_contents">-</a><?php if ($vars['entity']->canEdit()) { ?><a href="javascript:void(0);" class="toggle_box_edit_panel"><?php echo elgg_echo('edit'); ?></a><?php } ?>
	<?php if (isset($link)) { ?>
		<h1><a href="<?php echo $link; ?>"><?php echo $title; ?></a></h1>
	<?php } else { ?>
		<h1><?php echo $title; ?></h1>
	<?php } ?>		
	</div>
	<?php

		if ($vars['entity']->canEdit()) {
	
	?>
	<div class="collapsable_box_editpanel"><?php 
		
		echo elgg_view('widgets/editwrapper', 
						array(
								'body' => elgg_view("widgets/{$handler}/edit",$vars),
								'entity' => $vars['entity']
							  )
					   ); 
		
	?></div><!-- /collapsable_box_editpanel -->
	<?php

		}
	
	?>
	<div class="collapsable_box_content">
		<?php 

		echo "<div id=\"widgetcontent{$vars['entity']->getGUID()}\">";
		
		
	} else { // end if callback != "true"

		if (elgg_view_exists("widgets/{$handler}/view"))
			echo elgg_view("widgets/{$handler}/view",$vars);
		else
			echo elgg_echo('widgets:handlernotfound');

?>

<script language="javascript">
 $(document).ready(function(){
   	setup_avatar_menu();
 });

</script>


<?php
		
	}
		
	if ($callback != "true") {
		echo elgg_view('ajax/loader');
		echo "</div>";
		
		?>
	</div><!-- /.collapsable_box_content -->
	</div><!-- /.collapsable_box -->	
	</div>
	
<script type="text/javascript">
$(document).ready(function() {

	$("#widgetcontent<?php echo $vars['entity']->getGUID(); ?>").load("<?php echo $vars['url']; ?>pg/view/<?php echo $vars['entity']->getGUID(); ?>?shell=no&username=<?php echo page_owner_entity()->username; ?>&context=widget&callback=true");

	// run function to check for widgets collapsed/expanded state
	var forWidget = "widget<?php echo $vars['entity']->getGUID(); ?>";
	widget_state(forWidget);


});
</script>
	
<?php

	}

?>