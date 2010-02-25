<?php

	/**
	 * Elgg dgroup icon
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed.
	 * @uses $vars['size'] The size - small, medium or large. If none specified, medium is assumed. 
	 */

	$dgroup = $vars['entity'];
	
	if ($dgroup instanceof ElggGroup) {
	// Get size
	if (!in_array($vars['size'],array('small','medium','large','tiny','master','topbar')))
		$vars['size'] = "medium";
			
	// Get any align and js
	if (!empty($vars['align'])) {
		$align = " align=\"{$vars['align']}\" ";
	} else {
		$align = "";
	}
	
	if ($icontime = $vars['entity']->icontime) {
		$icontime = "{$icontime}";
	} else {
		$icontime = "default";
	}
	$name = '';
	
	$imgurl = $vars['entity']->getIcon($vars['size']);
	
/*
	$entity = $vars['entity'];
	$type = $entity->type;
	$viewtype = $params['viewtype'];
	$size = $params['size'];
	
	if ($icontime = $entity->icontime) {
		$icontime = "{$icontime}";
	} else {
		$icontime = "default";
	}
	
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $entity->owner_guid;
	$filehandler->setFilename("dgroups/" . $entity->guid . $size . ".jpg");
	
	if ($filehandler->exists()) {
		$imgurl = $CONFIG->url . "pg/dgroupicon/{$entity->guid}/$size/$icontime.jpg";
	}
*/
?>

<div class="dgroupicon">
<a href="<?php echo $vars['entity']->getURL(); ?>" class="icon" ><img src="<?php echo $imgurl; ?>" border="0" <?php echo $align; ?> title="<?php echo $name; ?>" <?php echo $vars['js']; ?> /></a>
</div>

<?php

	}

?>