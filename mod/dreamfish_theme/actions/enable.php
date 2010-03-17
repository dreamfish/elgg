<?php
	/**^M
         * Elgg registration action^M
         * ^M
         * @package Elgg^M
         * @subpackage Core^M
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2^M
         * @author Curverider Ltd^M
         * @copyright Curverider Ltd 2008-2009^M
         * @link http://elgg.org/^M
         */
	
	
	// Get variables
	if (isadminloggedin()) { 
		set_user_validation_status(get_input('guid'), true, 'admin');
		system_message('user validated');
		forward("/");
	}

?>
