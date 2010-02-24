<?php
/**
 * Blog type selector view
 *
 * @package ElggBlogExtended
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2009
 * @link http://www.somosmas.org
 *
 */
global $CONFIG;

if(get_plugin_setting("extra_types","blogextended")=="yes"){

  $value = "";
  if(isset($vars["entity"])){
    $value = $vars["entity"]->blog_type;
  }

  $options = $CONFIG->blogextended;
  ?>
<p><label><?php echo elgg_echo("blog:type"); ?></label><br />
  <?php echo elgg_view("input/pulldown",array("internalname"=>"blog_type","options_values"=>$options,"value"=>$value)); ?>
</p>
<?php
}
?>