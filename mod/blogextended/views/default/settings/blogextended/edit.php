<?php
/**
 * Elgg extended configuration view
 *
 * @package ElggBlogExtended
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2009
 * @link http://www.somosmas.org
 */

$widget_view = (empty($vars['entity']->view))?'create':$vars['entity']->view;
$extra_types = (empty($vars['entity']->extra_types))?"yes":$vars['entity']->extra_types;
$groupcontents = (empty($vars['entity']->groupcontents))?"yes":$vars['entity']->groupcontents;
$overwrite = (empty($vars['entity']->iconoverwrite))?"yes":$vars['entity']->iconoverwrite;

$vars['entity']->extra_types
?>
<p><?php echo elgg_echo('blog:widget:default_view'); ?>
<?php
  echo elgg_view('input/pulldown', array(
			'internalname' => 'params[view]',
			'options_values' => array('create' => elgg_echo('blog:widget:default'),
									  'publish' => elgg_echo('blog:widget:compact'),
             ),
			'value' => $widget_view
));
?></p>

<p><?php echo elgg_echo('blog:extratypes:enable'); ?>
<?php
  echo elgg_view('input/pulldown', array(
			'internalname' => 'params[extra_types]',
			'options_values' => array('yes' => elgg_echo('option:yes'),
									  'no' => elgg_echo('option:no'),
             ),
			'value' => $extra_types
));
?></p>

<p><?php echo elgg_echo('blog:group:contents'); ?>
<?php
  echo elgg_view('input/pulldown', array(
			'internalname' => 'params[groupcontents]',
			'options_values' => array('yes' => elgg_echo('option:yes'),
									  'no' => elgg_echo('option:no'),
             ),
			'value' => $groupcontents
));
?></p>
<p><?php echo elgg_echo('blog:group:iconoverwrite'); ?>
<?php
  echo elgg_view('input/pulldown', array(
			'internalname' => 'params[iconoverwrite]',
			'options_values' => array('yes' => elgg_echo('option:yes'),
									  'no' => elgg_echo('option:no'),
             ),
			'value' => $overwrite
));
?></p>
