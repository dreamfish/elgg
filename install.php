<?php

	/**
	 * Elgg install script
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	/**
	 * Start the Elgg engine
	 */
		require_once(dirname(__FILE__) . "/engine/start.php");
		global $CONFIG;
		
		elgg_set_viewtype('failsafe');
	/**
	 * If we're installed, go back to the homepage
	 */
		if ((is_installed() && is_db_installed() && datalist_get('installed')))
			forward("index.php");
		
	/**
	 * Install the database
	 */
		if (!is_db_installed()) {
			validate_platform();
			run_sql_script(dirname(__FILE__) . "/engine/schema/mysql.sql");
			init_site_secret();
			system_message(elgg_echo("installation:success"));
		}
		
	/**
	 * Load the front page
	 */
		page_draw(elgg_echo("installation:settings"), elgg_view_layout("one_column", elgg_view("settings/install")));

?>