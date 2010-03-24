<?php

	/**
	 * Elgg access level input
	 * Displays a pulldown input field
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * 
	 */

	if (isset($vars['class'])) $class = $vars['class'];
	if (!$class) $class = "input-access";
	
	if (!array_key_exists('value', $vars) || $vars['value'] == ACCESS_DEFAULT)
		$vars['value'] = get_default_access();
			

		if ((!isset($vars['options'])) || (!is_array($vars['options'])))
		{
			$vars['options'] = array();
			$vars['options'] = get_write_access_array();
		}
		
		if (is_array($vars['options']) && sizeof($vars['options']) > 0) {	 
			 
?>

<select name="<?php echo $vars['internalname']; ?>" <?php if (isset($vars['js'])) echo $vars['js']; ?> <?php if ((isset($vars['disabled'])) && ($vars['disabled'])) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>">
<?php

    foreach($vars['options'] as $key => $option) {
    	//DF: begin hack 
    	//the access list is different if it is a project or a group
    	//groups and projects are both of type group, but of different subtype
    	//so first get the subtype
    	$page_owner = page_owner_entity();    	
    	
    	if ($page_owner)
    	{
    		$type = page_owner_entity()->getSubtype();
	    	$start = substr($option,0,strlen($type));
	    	
	    	//if the option starts with project or dgroup
	    	if ( (stristr($option,'project') ) || (stristr($option,'group')) )
	    	{
	    		
	    		//if it's a group, we don't need the project access list
	    		//if it's a project, we don't need the group access list
	    		if ( strcasecmp($start, $type))
	    			continue;
	    	}
    	}
    	//DF: end hack
    	
        if ($key != $vars['value']) {        	
            echo "<option value=\"{$key}\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
        } else {
            echo "<option value=\"{$key}\" selected=\"selected\">". htmlentities($option, ENT_QUOTES, 'UTF-8') ."</option>";
        }
    	
    }

?> 
</select>

<?php

		}		

?>