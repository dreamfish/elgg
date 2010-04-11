<?php

/**
 * Elgg blog extended individual post view. Overwrite blog/views/default/object/blog.php
 *
 * Add the support for pre/post description fields
 *
 * @package ElggBlogExtended
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Ramirez <diego@somosmas.org>
 * @copyright Corporacion Somos mas 2009
 * @link http://somosmas.org/
 *
 * @uses $vars['entity'] Optionally, the blog post to view
 */

if (isset($vars['entity'])) {
  //display comments link?
  if ($vars['entity']->comments_on == 'Off') {
    $comments_on = false;
  } else {
    $comments_on = true;
  }

  if (get_context() == "search") {

    //display the correct layout depending on gallery or list view
    if (get_input('search_viewtype') == "gallery" && $vars['entity'] instanceof ElggObject) {

      //display the gallery view
      echo elgg_view("blog/gallery",$vars);

    } else {

      echo elgg_view("blog/listing",$vars);

    }


  } else {
    if ($vars['entity'] instanceof ElggObject) {
       
      global $CONFIG;
      $url = $vars['entity']->getURL();
      $url = $CONFIG->url . 'pg/gblog/read/' . $vars['entity']->guid . '/' . str_replace(' ', '-', $vars['entity']->title);
      $owner = $vars['entity']->getOwnerEntity();
      $canedit = $vars['entity']->canEdit();
       
    } else {
       
      $url = 'javascript:history.go(-1);';
      $owner = $vars['user'];
      $canedit = false;
       
    }

    ?>
<div class="contentWrapper singleview">

<div class="blog_post">
<h3><a href="<?php echo $url; ?>"><?php echo $vars['entity']->title; ?></a>
<?php
if(!empty($vars["entity"]->content_owner)){
  $content_owner = get_entity($vars["entity"]->content_owner);
  $link = '<a href="'.$content_owner->getURL().'">'.$content_owner->name.'</a>';
}
?>
</h3>
<p style="font-size:.9em;color:#999999"><?php

echo sprintf(elgg_echo("blog:strapline"),
date("F j, Y",$vars['entity']->time_created)
);

?> <?php echo elgg_echo('by'); ?> <a
	href="<?php echo $vars['url']; ?>pg/profile/<?php echo $owner->username; ?>"><?php echo $owner->name; ?></a>
&nbsp; <!-- display the comments link --> <?php
if($comments_on && $vars['entity'] instanceof ElggObject){
  //get the number of comments
  $num_comments = elgg_count_comments($vars['entity']);
  ?> <a href="<?php echo $url; ?>"><?php echo sprintf(elgg_echo("comments")) . " (" . $num_comments . ")"; ?></a><br />
  <?php
}
?></p>

<div class="blog_post_body"><!-- display the actual blog post --> <?php

echo elgg_view('output/longtext',array('value' => $vars['entity']->description));

?></div>
<div class="clearfloat"></div>

<!-- Blog type -->
<p style="font-size:.9em;color:#999999">Category: <?php
$type = $vars["entity"]->blog_type;
if(!empty($type) && $type!="--"){
  echo elgg_view('output/tags', array('tags' => elgg_echo($vars["entity"]->blog_type)));
}
?></p>

<!-- display tags --> <?php

$tags = elgg_view('output/tags', array('tags' => $vars['entity']->tags));
if (!empty($tags)) {
  echo '<p class="tags">' . $tags . '</p>';
}

$categories = elgg_view('categories/view', $vars);
if (!empty($categories)) {
  echo '<p class="categories">' . $categories . '</p>';
}

?>
<div class="clearfloat"></div>


<!-- display edit options if it is the blog post owner -->
<p class="options"><?php

if ($canedit) {
   
  ?> <a
	href="<?php echo $vars['url']; ?>mod/blog/edit.php?blogpost=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("edit"); ?></a>
&nbsp; <?php

echo elgg_view("output/confirmlink", array(
																	'href' => $vars['url'] . "action/blog/delete?blogpost=" . $vars['entity']->getGUID(),
																	'text' => elgg_echo('delete'),
																	'confirm' => elgg_echo('deleteconfirm'),
));

// Allow the menu to be extended
echo elgg_view("editmenu",array('entity' => $vars['entity']));

?> <?php
}

?></p>
</div>
</div>

<?php

// If we've been asked to display the full view
if (isset($vars['full']) && $vars['full'] == true && $comments_on == 'on' && $vars['entity'] instanceof ElggEntity) {
  echo elgg_view_comments($vars['entity']);
}

  }

}
?>