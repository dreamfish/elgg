<?php
/**
 * Elgg blog extended widget publish view
 *
 * @package ElggBlogExtended
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2009
 * @link http://www.somosmas.org
 */

$statement = $vars['statement'];
$time = $vars["time"];
$performed_by = $statement->getSubject();
$object = $statement->getObject();

$string = "<a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";

 if(!empty($object->content_owner)){
  $owner = get_entity($object->content_owner);
  $link = '<a href="'.$owner->getURL().'">'.$owner->name.'</a>';
  $string.='<span class="river_item_time">&nbsp;&nbsp;('.sprintf(elgg_echo("publish:for"),$link).')</span>';
}
if(!empty($object->blog_type)){
  $string .= "<p class=\"strapline\">&nbsp;".elgg_view('output/tags', array('tags' => elgg_echo($object->blog_type)))."</p>";
}
$string .= "<p class=\"tags\">&nbsp;".elgg_view('output/tags', array('tags' => elgg_echo($object->tags)))."</p>";


?>

<?php echo $string; ?>