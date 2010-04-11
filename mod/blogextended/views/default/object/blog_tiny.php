<?php

/**
 * Elgg blog individual post view using a tiny presentation
 *
 * @package ElggBlog
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Ramirez <diego@somosmas.org>
 * @copyright Corporacion Somos mas 2009
 * @link http://somosmas.org/
 *
 * @uses $vars['entity'] Optionally, the blog post to view
 */

if (isset($vars['entity'])) {
?>

<div class="blog_post">
<!-- display the user icon -->
<div class="blog_post_icon"><?php
$owner = $vars['entity']->get("content_owner");
if(get_plugin_setting("iconoverwrite","blogextended")== "yes" && !empty($owner)){
  echo elgg_view("profile/icon",array('entity' => get_entity($owner), 'size' => 'small','entity_id'=>$vars['entity']->guid));
}
else{
  echo elgg_view("profile/icon",array('entity' => $vars['entity']->getOwnerEntity(), 'size' => 'small','entity_id'=>$vars['entity']->guid));
}
?></div>
<div class="search_listing_info">
<p><b><a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo $vars['entity']->title; ?></a></b></p>
<!-- Blog type -->
<p>
<?php
  $type = $vars["entity"]->blog_type;
  if(!empty($type) && $type!="--"){
    echo elgg_view('output/tags', array('tags' => elgg_echo($vars["entity"]->blog_type)));
  }
?>
</p>
<p ><?php echo friendly_time($vars['entity']->time_created);?>&nbsp;
<?php echo elgg_echo('by'); ?> <a href="<?php echo $vars['url']; ?>pg/blog/<?php echo $vars['entity']->getOwnerEntity()->username; ?>"><?php echo $vars['entity']->getOwnerEntity()->name; ?></a>
&nbsp;
</p>
</div>
<div class="clearfloat"></div>
</div>
<?php
}
?>