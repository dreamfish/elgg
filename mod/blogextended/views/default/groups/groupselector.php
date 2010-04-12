<?php
/**
 * Blog owner selector view
 *
 * @package ElggBlogExtended
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2009
 * @link http://www.somosmas.org
 *
 */

if(get_plugin_setting("groupcontents","blogextended")=="yes"){

  $field_label = elgg_echo("content:owner");
  if(isset($vars["label"])){
    $field_label = $vars["label"];
  }

  $value = "";
  if(isset($vars["entity"])){
    $value = $vars["entity"]->content_owner;
  }

  $options = array(
  ""=>elgg_echo("my:profile"),
  );

  $objects = get_entities_from_relationship("member",page_owner(),false,"group");
  if(!empty($objects)){
    foreach($objects as $object){
      $options["{$object->guid}"]=$object->name;
    }
  }

  if (isset($vars["assign_to"]))
  {
    $options = array();
    $value = $vars["assign_to"];
    $group = get_entity($value);
    $options[$value] = $group->name;
  }
  ?>

<p><label><?php echo $field_label; ?></label><br />
  <?php echo elgg_view("input/pulldown",array("internalname"=>"content_owner","options_values"=>$options,"value"=>$value)); ?>
</p>
<?php
}
?>