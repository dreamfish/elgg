<?php

	/**
	 * Elgg bookmarks plugin form
	 * 
	 * @package ElggBookmarks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Have we been supplied with an entity?
		if (isset($vars['entity'])) {
			
			$guid = $vars['entity']->getGUID();
			$title = $vars['entity']->title;
			$description = $vars['entity']->description;
			$address = $vars['entity']->address;
			$tags = $vars['entity']->tags;
			$access_id = $vars['entity']->access_id;
			$shares = $vars['entity']->shares;
			$owner = $vars['entity']->getOwnerEntity();
			$highlight = 'default';
			
		} else {
			
			$guid = 0;
			$title = get_input('title',"");
			$description = "";
			$address = get_input('address',"");
			$highlight = 'all';
			
			if ($address == "previous")
				$address = $_SERVER['HTTP_REFERER'];
			$tags = array();
			
			if (defined('ACCESS_DEFAULT'))
				$access_id = ACCESS_DEFAULT;
			else
				$access_id = 0;
			$shares = array();
			$owner = $vars['user'];
			
		}

?>
<div class="contentWrapper">
	<form action="<?php echo $vars['url']; ?>action/bookmarks/add" method="post">
	
		<p>
			<label>
				<?php 	echo elgg_echo('title'); ?>
				<?php

						echo elgg_view('input/text',array(
								'internalname' => 'title',
								'value' => $title,
						)); 
				
				?>
			</label>
		</p>
		<p>
			<label>
				<?php 	echo elgg_echo('bookmarks:address'); ?>
				<?php

						echo elgg_view('input/url',array(
								'internalname' => 'address',
								'value' => $address,
						)); 
				
				?>
			</label>
		</p>
		<p class="longtext_editarea">
			<label>
				<?php 	echo elgg_echo('description'); ?>
				<br />
				<?php

						echo elgg_view('input/longtext',array(
								'internalname' => 'description',
								'value' => $description,
						)); 
				
				?>
			</label>
		</p>
		<p>
			<label>
				<?php 	echo elgg_echo('tags'); ?>
				<?php

						echo elgg_view('input/tags',array(
								'internalname' => 'tags',
								'value' => $tags,
						)); 
				
				?>
			</label>
		</p>
		<p>
			<label><?php echo elgg_echo("bookmarks:with"); ?></label><br />
			<?php

				//echo elgg_view('bookmarks/sharing',array('shares' => $shares, 'owner' => $owner));
				if ($friends = get_entities_from_relationship('friend',$owner->getGUID(),false,'user','', 0, "", 9999)) {
					echo elgg_view('friends/picker',array('entities' => $friends, 'internalname' => 'shares', 'highlight' => $highlight));
				}
			
			?>
		</p>
		<p>
			<label>
				<?php 	echo elgg_echo('access'); ?>
				<?php

						echo elgg_view('input/access',array(
								'internalname' => 'access',
								'value' => $access_id,
						)); 
				
				?>
			</label>
		</p>
		<p>
			<input type="hidden" name="bookmark_guid" value="<?php echo $guid; ?>" />
			<input type="submit" value="<?php echo elgg_echo('save'); ?>" />
		</p>
	
	</form>
</div>