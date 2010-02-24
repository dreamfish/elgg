<?php
/**
 * Elgg blog extended edit/add page. Overwrite blog/views/default/blog/forms/edit.php
 *
 * Add the support for pre/post description fields
 *
 * @package ElggBlogExtended
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2009
 * @link http://www.somosmas.org
 *
 * @uses $vars['object'] Optionally, the blog post to edit
 */

// Set title, form destination
if (isset($vars['entity'])) {
  $title = sprintf(elgg_echo("blog:editpost"),$object->title);
  $action = "blog/edit";
  $title = $vars['entity']->title;
  $body = $vars['entity']->description;
  $tags = $vars['entity']->tags;
  if ($vars['entity']->comments_on == 'Off') {
    $comments_on = false;
  } else {
    $comments_on = true;
  }
  $access_id = $vars['entity']->access_id;
} else  {
  $title = elgg_echo("blog:addpost");
  $action = "blog/add";
  $tags = "";
  $title = "";
  $description = "";
  $comments_on = true;
  $description = "";
  if (defined('ACCESS_DEFAULT')){
    $access_id = ACCESS_DEFAULT;
  }else{
    $access_id = 0;
  }
}

// Just in case we have some cached details
if (empty($body)) {
  $body = $vars['user']->blogbody;
  if (!empty($body)) {
    $title = $vars['user']->blogtitle;
    $tags = $vars['user']->blogtags;
  }
}

?>

<?php
$title_label = elgg_echo('title');
$title_textbox = elgg_view('input/text', array('internalname' => 'blogtitle', 'value' => $title));

$text_label = elgg_echo('blog:text');
$text_textarea = elgg_view('input/longtext', array('internalname' => 'blogbody', 'value' => $body));

$tag_label = elgg_echo('tags');
$tag_input = elgg_view('input/tags', array('internalname' => 'blogtags', 'value' => $tags));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array('internalname' => 'access_id', 'value' => $access_id));

$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));

if($comments_on){
  $comments_on_switch = "checked=\"checked\"";
}else{
  $comment_on_switch = "";
}
$conversation = elgg_echo('Conversation');
$publish = elgg_echo('publish');
$cat = elgg_echo('categories');
$preview = elgg_echo('blog:preview');
$privacy = elgg_echo('access');
$savedraft = elgg_echo('blog:draft:save');
$draftsaved = elgg_echo('blog:draft:saved');
$never = elgg_echo('blog:never');
$allowcomments = elgg_echo('blog:comments:allow');

// INSERT EXTRAS HERE
$extras = elgg_view('categories',$vars);
if (!empty($extras)) $extras = '<div id="blog_edit_sidebar">' . $extras . '</div>';


if (isset($vars['entity'])) {
  $entity_hidden = elgg_view('input/hidden', array('internalname' => 'blogpost', 'value' => $vars['entity']->getGUID()));
} else {
  $entity_hidden = '';
}

$fields_before = elgg_view("blog/fields_before",$vars);
$fields_after = elgg_view("blog/fields_after",$vars);

$form_body = <<<EOT

<div id="two_column_left_sidebar_210">

  		<div id="blog_edit_sidebar">
		<div id="content_area_user_title">
			<div class="preview_button"><a  onclick="javascript:saveDraft(true);return true;">{$preview}</a></div>
		<h2>{$publish}</h2></div>
		<div class="publish_controls">
			<p>
				<a href="#" onclick="javascript:saveDraft(false);return false;">{$savedraft}</a>
			</p>
		</div>
		<div class="publish_options">
			<!-- <p><b>{$publish}:</b> now <a href="">edit</a></p> -->
			<p class="auto_save">{$draftsaved}: <span id="draftSavedCounter">{$never}</span></p>
		</div>
		<div class="blog_access">
			<p>{$privacy}: {$access_input}
		</p></div>
		<div class="publish_blog">
			{$submit_input}
		</div>
	</div>

	<div id="blog_edit_sidebar">
		<div id="content_area_user_title"><h2>{$conversation}</h2></div>
		<div class="allow_comments">
			<p><label>
				<input type="checkbox" name="comments_select"  {$comments_on_switch} /> {$allowcomments}
				</label></p>
		</div>
	</div>

	{$extras}

</div><!-- /two_column_left_sidebar_210 -->

<!-- main content -->
<div id="two_column_left_sidebar_maincontent">


<p>
<label>$title_label</label><br />
$title_textbox
</p>

<!-- start of before fields -->
$fields_before
<!-- end of before fields -->

<p>
<label>$text_label</label><br />
$text_textarea
</p>

<!-- start of after fields -->
$fields_after
<!-- end of after fields -->

<p>
<label>$tag_label</label><br />
$tag_input
</p>
<p>
<label>$access_label</label><br />
$access_input
</p>
<p>
$entity_hidden
<!--$submit_input-->
</p>
</div><div class="clearfloat"></div><!-- /two_column_left_sidebar_maincontent -->
EOT;

echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body, 'internalid' => 'blogPostForm',"enctype"=>"multipart/form-data"));
?>
<script type="text/javascript">
	setInterval( "saveDraft(false)", 120000);
	function saveDraft(preview) {

		temppreview = preview;

		var drafturl = "<?php echo $vars['url']; ?>mod/blog/savedraft.php";
		var temptitle = $("input[@name='blogtitle']").val();
		var tempbody = $("textarea[@name='blogbody']").val();
		var temptags = $("input[@name='blogtags']").val();

		var postdata = { blogtitle: temptitle, blogbody: tempbody, blogtags: temptags };

		$.post(drafturl, postdata, function() {
			var d = new Date();
			var mins = d.getMinutes() + '';
			if (mins.length == 1) mins = '0' + mins;
			$("span#draftSavedCounter").html(d.getHours() + ":" + mins);
			if (temppreview == true) {
				$("form#blogPostForm").attr("action","<?php echo $vars['url']; ?>mod/blog/preview.php");
				$("input[@name='submit']").click();
				//$("form#blogPostForm").submit();
				//document.blogPostForm.submit();
			}
		});

	}

</script>