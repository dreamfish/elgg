<?php 
	/**
	* Profile Manager
	* 
	* Datepicker
	* 
	* @uses $vars['value'] The current value, if any
	* @uses $vars['internalname'] The name of the input field
	* 
	* @package profile_manager
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/
 	
	global $datepicker;
 	
	$dateformat = elgg_echo("profile_manager:datepicker:input:dateformat");
	$dateformat_js = elgg_echo("profile_manager:datepicker:input:dateformat_js");
	$locale_js = elgg_echo("profile_manager:datepicker:input:localisation");
	
    // only include js once
    if (empty($datepicker)) {
        echo <<< END
        
<script type="text/javascript" src="{$vars['url']}mod/profile_manager/vendors/jquery.datepick.package-3.5.2/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="{$vars['url']}mod/profile_manager/vendors/jquery.datepick.package-3.5.2/redmond.datepick.css">        
END;
		if(!empty($locale_js)){
			echo "<script type='text/javascript' src='" . $vars['url'] . "mod/profile_manager/vendors/jquery.datepick.package-3.5.2/" . $locale_js . "'></script>";
		}
        $datepicker = 1;
    } else {
    	$datepicker++;
    }
    
    $internal_id = sanitise_string(str_replace("]", "_", str_replace("[" , "_" ,$vars['internalname']))) . $datepicker;
	
    $val = $vars['value'];
    if($val)
    $dateval = strftime($dateformat, $val);
    
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#<?php echo $internal_id;?>').datepick({
			dateFormat: '<?php echo $dateformat_js;?>', 
		    altField: '#<?php echo $internal_id; ?>_alt', 
		    altFormat: $.datepick.TIMESTAMP,
		    buttonImage: "<?php echo $vars['url'];?>mod/profile_manager/vendors/jquery.datepick.package-3.5.2/calendar.gif",
			buttonImageOnly: true, 
			showOn: 'both',
			yearRange: '-90:+10',
			onSelect: function(value, date) { 		    	
				var curval = $('#<?php echo $internal_id; ?>_alt').val();
				if(curval > 0){
		    		$('#<?php echo $internal_id; ?>_alt').val((curval/1000) - (new Date().getTimezoneOffset() * 60));
				} 
			}
			});
	});

</script>
<input class="datepicker_hidden" type="text" READONLY name="<?php echo $vars['internalname']; ?>" value="<?php echo $val; ?>" id="<?php echo $internal_id; ?>_alt" /> <input type="text" READONLY id="<?php echo $internal_id; ?>" value="<?php echo $dateval; ?>" style="width:200px"/>