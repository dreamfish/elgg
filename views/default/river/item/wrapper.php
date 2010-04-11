<?php

	/**
	 * Elgg river item wrapper.
	 * Wraps all river items.
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */


?>

	<div class="river_item">
		<div class="river_<?php echo $vars['item']->type; ?>">
			<div class="river_<?php echo $vars['item']->subtype; ?>">
				<div class="river_<?php echo $vars['item']->action_type; ?>">				
					<div class="river_<?php echo $vars['item']->type; ?>_<?php if($vars['item']->subtype) echo $vars['item']->subtype . "_"; ?><?php echo $vars['item']->action_type; ?>">
					<p>
						<?php
		
								echo $vars['body'];
				
						?>
						<span class="river_item_time">
							(<?php
				
								echo friendly_time($vars['item']->posted);
							
							?>)
						</span>
					</p>
					</div>
				</div>				
			</div>
		</div>
	</div>