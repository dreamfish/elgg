<?php
	/**
	 * Elgg Pages: Add group menu
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
	 
?>
<?php 
if (isadminloggedin()) { 

$user = get_entity($vars['entity']->guid);

if (!$user->validated) {
?>
<p>
		<a href="<?php echo $vars['url']; ?>action/user/enable?guid=<?php echo $vars['entity']->guid; ?>">Enable User</a>	
</p>
<?php
}

else { ?>
<p>User enabled</p>
<? } 

}?>