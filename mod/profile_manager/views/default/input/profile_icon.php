<?php 
	/**
	* Profile Manager
	* 
	* Register profile icon input field
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/

	echo "<p><label>";
	echo elgg_echo("profile_manager:register:profile_icon") . "<br />";
	echo elgg_view("input/file", array("internalname"=>"profile_icon"));
	echo "</label></p>";
?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#register-box form").attr("enctype", "multipart/form-data").attr("encoding", "multipart/form-data");
	});
</script>