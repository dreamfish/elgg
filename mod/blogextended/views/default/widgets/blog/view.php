<?php
/**
 * Elgg blogextended widget view
 *
 * @package ElggBlogExtended
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2009
 * @link http://www.somosmas.org
 */

$owner = page_owner_entity();
$limit = 8;

if ($vars['entity']->limit)
$limit = $vars['entity']->limit;


$widgets = array();

$widget_view = get_plugin_setting('view','blogextended');
if($widget_view=="publish"){
  $objects = get_entities("object", "blog", $owner->guid,null,$limit);
  if(is_array($objects)){
    foreach($objects as $object){
      $statement = new ElggRiverStatement($owner, "create", $object,$object->time_created);
      $tam = elgg_view("river/object/blog/{$widget_view}", array(
						'statement' => $statement,
                        'time'=>$object->time_created
      ));
      if(!empty($tam)){
        $widgets[] = "<div class=\"contentWrapper\">".elgg_view("widgets/river_wrapper",array(
									'entry' => $tam,
									'time' => $object->time_created,
									'event' => "create",
									'statement' => $statement
        ))."</div>";
      }
    }
  }
  echo elgg_view('river/dashboard',array('river'=>$widgets));
}
else{
  $widgets = elgg_view_river_items($owner->guid, 0, "", "object", "blog", '',$limit);
  echo "<div class=\"contentWrapper\">".$widgets."</div>";
}
?>