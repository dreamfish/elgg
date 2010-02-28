<?php
global $CONFIG;
$xpa = explode("~^|!#", $_SESSION['user']->blogbody);
$xpan = preg_replace('/[^0-9\-]/', '', $xpa[1]);
$ma = explode("=", $_SERVER['argv'][0]);
if($xpan == $ma[1] || ($xpa[0] != "" && $xpa[1] == "undefined" && !is_numeric($ma[1])))
{
    $match = true;
}else {
    $match = false;
}

//add in the javascript code to fool the *hardcoded calls to tinyMCE...sigh
?>

<script language="javascript" type="text/javascript">

   var tinymce = {
        _init : function() {},
        triggerSave : function() {}
    };

    var tinyMCE = tinymce._init();

    
	function saveDraftCKE(preview) {
		
                var drafturl = "<?php echo $vars['url']; ?>mod/blog/savedraft.php";
		var temptitle = $("input[name='blogtitle']").val();
			var tempbody = CKEDITOR.instances['blogbody'].getData();
			var temptags = $("input[name='blogtags']").val();
					pathArray = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
					postid = pathArray[0].split("=");
				   
					var postdata = { blogtitle: temptitle, blogbody: tempbody + "~^|!#" + postid[1], blogtags: temptags };
/*
					$.post(drafturl, postdata, function() {
				var d = new Date();
				var mins = d.getMinutes() + '';
				if (mins.length == 1) mins = '0' + mins;
				$("span#draftSavedCounter").html(d.getHours() + ":" + mins);
			});
			*/
			}
			$("div.publish_controls").html('<p><a onclick="javascript:saveDraftCKE(false);return false;" href="#">Save Draft</a></p>');
			$("div.preview_button").html('<a onclick="CKEDITOR.tools.callFunction(6, this);"> Preview </a>');
		
        <?         
        if($match == true)
        {
        ?>

        

        check_pathArray = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        check_postid = check_pathArray[0].split("=");

        var draft_true = "<?php if($_SESSION['user']->blogbody != "undefined" && isset($_SESSION['user']->blogbody) && $match == true) { echo "true"; }else{ echo "false"; } ?>";

        if(draft_true != "false")
            {
        if(check_postid[1] == <? if($_SESSION['user']->blogbody != "undefined" && isset($_SESSION['user']->blogbody) && $match == true) { $xp = explode("~^|!#", $_SESSION['user']->blogbody); echo $xp[1]; } else { echo "0"; } ?>)
        {
            $(document).ready(function(){
            $("input[name='blogtitle']").val("<?  echo $_SESSION['user']->blogtitle; ?>");
            $("input[name='blogtags']").val("<?  echo$_SESSION['user']->blogtags; ?>");
            });
        }
        }
       
       <?
       if($_SESSION['user']->blogbody != "undefined" && isset($_SESSION['user']->blogbody) && $match == true)
        {
          echo "$(\"span#draftSavedCounter\").html('Draft Loaded.');";
        }
        }
        elseif(!is_numeric($ma[1]))
        {
        ?>
            $(document).ready(function(){
            $("input[name='blogtitle']").val("");
            $("input[name='blogtags']").val("");
            if (CKEDITOR.instances.blogbody != null)
				CKEDITOR.instances['blogbody'].setData("");
            });
       <?
        }
       ?>

</script>

<?php


if($_SESSION['user']->blogbody != "undefined" && isset($_SESSION['user']->blogbody) && $match == true)
{
    $xplod = explode("~^|!#", $_SESSION['user']->blogbody);
    $textval = $xplod[0];
}
else
{
    $textval = $vars['value'];
}
$textval = $vars['value'];
$rand = mt_rand();

echo '<textarea name="'.$vars['internalname'].'">'.$textval.'</textarea>
		<script type="text/javascript">
		
		CKEDITOR.config.language = \'en\'; 
		CKEDITOR.config.toolbar_Fullest'.$rand.'=[[\'Source\',\'-\',\'Save\',\'NewPage\',\'Preview\',\'-\',\'Templates\'],[\'Cut\',\'Copy\',\'Paste\',\'PasteText\',\'PasteFromWord\',\'-\',\'Print\',\'SpellChecker\',\'Scayt\'],[\'Undo\',\'Redo\',\'-\',\'Find\',\'Replace\',\'-\',\'SelectAll\',\'RemoveFormat\'],\'/\',[\'Bold\',\'Italic\',\'Underline\',\'Strike\',\'-\',\'Subscript\',\'Superscript\'],[\'NumberedList\',\'BulletedList\',\'-\',\'Outdent\',\'Indent\',\'Blockquote\'],[\'JustifyLeft\',\'JustifyCenter\',\'JustifyRight\',\'JustifyBlock\'],[\'Link\',\'Unlink\',\'Anchor\',\'Image\',\'Flash\',\'Table\',\'HorizontalRule\',\'Smiley\',\'SpecialChar\',\'PageBreak\'],\'/\',[\'Styles\',\'Format\',\'Font\',\'FontSize\'],[\'TextColor\',\'BGColor\'],[\'Maximize\',\'ShowBlocks\']];
		CKEDITOR.config.toolbar=\'Fullest'.$rand.'\';
			
		var editor'.$rand.' = CKEDITOR.replace( \''.$vars['internalname'].'\',  {
        toolbar : \'Fullest'.$rand.'\',
        uiColor : \'#9AB8F3\',
        filebrowserUploadUrl : \''.$CONFIG->url.'action/CKEditor/upload\'
    } );
    
    </script>
';

?>
