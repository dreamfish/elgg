<?php

	/**
	 * Elgg access permissions
	 * For users, objects, collections and all metadata
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	/**
	 * Get the list of access restrictions the given user is allowed to see on this site
	 *
	 * @uses get_access_array
	 * @param int $user_id User ID; defaults to currently logged in user
	 * @param int $site_id Site ID; defaults to current site 
	 * @param boolean $flush If set to true, will refresh the access list from the database
	 * @return string A list of access collections suitable for injection in an SQL call
	 */
		function get_access_list($user_id = 0, $site_id = 0, $flush = false) {
			
			global $CONFIG, $init_finished, $SESSION;
			static $access_list;
			
			if (!isset($access_list) || !$init_finished)
				$access_list = array();
				
			if ($user_id == 0) $user_id = $SESSION['id'];
			if (($site_id == 0) && (isset($CONFIG->site_id))) $site_id = $CONFIG->site_id;
			$user_id = (int) $user_id;
			$site_id = (int) $site_id;
			
			if (isset($access_list[$user_id])) return $access_list[$user_id];
			
			$access_list[$user_id] = "(" . implode(",",get_access_array($user_id, $site_id, $flush)) . ")";
			
			return $access_list[$user_id];
			
		}
		
	/**
	 * Gets an array of access restrictions the given user is allowed to see on this site
	 *
	 * @param int $user_id User ID; defaults to currently logged in user
	 * @param int $site_id Site ID; defaults to current site 
	 * @param boolean $flush If set to true, will refresh the access list from the database
	 * @return array An array of access collections suitable for injection in an SQL call
	 */
		function get_access_array($user_id = 0, $site_id = 0, $flush = false) {
			
			global $CONFIG, $init_finished;
			static $access_array, $acm, $ac; // Caches. $ac* flag whether we have executed a query previously, and stop it being run again if no data is returned.
			
			if (!isset($access_array) || (!isset($init_finished)) || (!$init_finished))
				$access_array = array(); 
				
			if ($user_id == 0) $user_id = get_loggedin_userid();
			
			if (($site_id == 0) && (isset($CONFIG->site_guid))) $site_id = $CONFIG->site_guid;
			$user_id = (int) $user_id;
			$site_id = (int) $site_id;
			
			if (empty($access_array[$user_id]) || $flush == true) {
				
				$query = "SELECT am.access_collection_id FROM {$CONFIG->dbprefix}access_collection_membership am ";
				$query .= " LEFT JOIN {$CONFIG->dbprefix}access_collections ag ON ag.id = am.access_collection_id ";
				$query .= " WHERE am.user_guid = {$user_id} AND (ag.site_guid = {$site_id} OR ag.site_guid = 0)";
				
				$tmp_access_array = array(ACCESS_PUBLIC); 
				if (isloggedin()) {
					$tmp_access_array[] = ACCESS_LOGGED_IN;
					
					// The following can only return sensible data if the user is logged in.
					
					if ($collections = get_data($query)) {
						foreach($collections as $collection)
							if (!empty($collection->access_collection_id)) $tmp_access_array[] = $collection->access_collection_id;
							
					}
						
					$query = "SELECT ag.id FROM {$CONFIG->dbprefix}access_collections ag  ";
					$query .= " WHERE ag.owner_guid = {$user_id} AND (ag.site_guid = {$site_id} OR ag.site_guid = 0)";
					
					if ($collections = get_data($query)) {
						foreach($collections as $collection)
							if (!empty($collection->id)) $tmp_access_array[] = $collection->id;
					}
					 
					
					global $is_admin;
					
					if (isset($is_admin) && $is_admin == true) {
						$tmp_access_array[] = ACCESS_PRIVATE;
					}

					$access_array[$user_id] = $tmp_access_array;
				}
				else
					return $tmp_access_array; // No user id logged in so we can only access public info
				
				
			} else {
				$tmp_access_array = $access_array[$user_id];
			}
			
			return $access_array[$user_id];
			
		}
		
	/**
	 * Gets the default access permission for new content
	 *
	 * @return int default access id (see ACCESS defines in elgglib.php)  
	 */
		function get_default_access($user=null)
		{
			global $CONFIG;
			
			if (!$CONFIG->allow_user_default_access) {
				return $CONFIG->default_access;
			}
			
			if (!$user) {
				if (isloggedin()) {
					$user = $_SESSION['user'];
				} else {
					return $CONFIG->default_access;
				}
			}
			
			if (false !== ($default_access = $user->getPrivateSetting('elgg_default_access'))) {
				return $default_access;
			} else {			
				return $CONFIG->default_access;
			}
		}
		
		/**
		 * Override the default behaviour and allow results to show hidden entities as well.
		 * THIS IS A HACK.
		 * 
		 * TODO: Replace this with query object!
		 */
		$ENTITY_SHOW_HIDDEN_OVERRIDE = false;
		
		/**
		 * This will be replaced. Do not use in plugins!
		 *
		 * @param bool $show
		 */
		function access_show_hidden_entities($show_hidden)
		{
			global $ENTITY_SHOW_HIDDEN_OVERRIDE;  
			$ENTITY_SHOW_HIDDEN_OVERRIDE = $show_hidden;  
		}
		
		/**
		 * This will be replaced. Do not use in plugins!
		 */
		function access_get_show_hidden_status()
		{
			global $ENTITY_SHOW_HIDDEN_OVERRIDE;  
			return $ENTITY_SHOW_HIDDEN_OVERRIDE;
		}
		
		/**
		 * Add annotation restriction
		 * 
		 * Returns an SQL fragment that is true (or optionally false) if the given user has 
		 * added an annotation with the given name to the given entity.
		 * 
		 * TODO: This is fairly generic so perhaps it could be moved to annotations.php
		 * 
		 * @param string $annotation_name name of the annotation
	 	 * @param string $entity_guid SQL string that evaluates to the GUID of the entity the annotation should be attached to
	 	 * @param string $owner_guid SQL string that evaluates to the GUID of the owner of the annotation	 	 * 
	 	 * @param boolean $exists If set to true, will return true if the annotation exists, otherwise returns false
	 	 * @return string An SQL fragment suitable for inserting into a WHERE clause
		 */
		
		function get_annotation_sql($annotation_name,$entity_guid,$owner_guid,$exists) {
			global $CONFIG;
			
			if ($exists) {
				$not = '';
			} else {
				$not = 'NOT';
			}
			
			$sql = <<<END
$not EXISTS (SELECT * FROM {$CONFIG->dbprefix}annotations a 
INNER JOIN {$CONFIG->dbprefix}metastrings ms ON (a.name_id = ms.id)
WHERE ms.string = '$annotation_name'
AND a.entity_guid = $entity_guid
AND a.owner_guid = $owner_guid)
END;
			return $sql;
		}
		
		/**
		 * Add access restriction sql code to a given query.
		 * 
		 * Note that if this code is executed in privileged mode it will return blank.
		 * 
		 * TODO: DELETE once Query classes are fully integrated
		 * 
		 * @param string $table_prefix Optional xxx. prefix for the access code.
		 */
		function get_access_sql_suffix($table_prefix = "",$owner=null)
		{
			global $ENTITY_SHOW_HIDDEN_OVERRIDE, $CONFIG;  
			
			$sql = "";
			$friends_bit = "";
			$enemies_bit = "";
			
			if ($table_prefix)
					$table_prefix = sanitise_string($table_prefix) . ".";
			
			$access = get_access_list();
			
			if (!isset($owner)) {
				$owner = get_loggedin_userid();
			}
			if (!$owner) $owner = -1;
			
			global $is_admin;
			
			if (isset($is_admin) && $is_admin == true) {
				$sql = " (1 = 1) ";
			} else if ($owner != -1) {				
				$friends_bit = $table_prefix.'access_id = '.ACCESS_FRIENDS.' AND ';
				$friends_bit .= "{$table_prefix}owner_guid IN (SELECT guid_one FROM {$CONFIG->dbprefix}entity_relationships WHERE relationship='friend' AND guid_two=$owner)";
				$friends_bit = '('.$friends_bit.') OR ';
				
				if ($CONFIG->user_block_and_filter_enabled) {
					// check to see if the user is in the entity owner's block list
					// or if the entity owner is in the user's filter list
					// if so, disallow access
					
					$enemies_bit = get_annotation_sql('elgg_block_list',"{$table_prefix}owner_guid",$owner,false);
					$enemies_bit = '('.$enemies_bit. ' AND '.get_annotation_sql('elgg_filter_list',$owner,"{$table_prefix}owner_guid",false).')';
				}
			}

			if (empty($sql))
				$sql = " $friends_bit ({$table_prefix}access_id in {$access} or ({$table_prefix}owner_guid = {$owner}) or ({$table_prefix}access_id = " . ACCESS_PRIVATE . " and {$table_prefix}owner_guid = $owner))";
			
			if ($enemies_bit) {
				$sql = "$enemies_bit AND ($sql)";
			}
				
			if (!$ENTITY_SHOW_HIDDEN_OVERRIDE)
				$sql .= " and {$table_prefix}enabled='yes'";
			return '('.$sql.')';
		}
		
		/**
		 * Determines whether the given user has access to the given entity
		 * 
		 * @param ElggEntity $entity The entity to check access for.
		 * @param ElggUser $user Optionally the user to check access for.
		 * 
		 * @return boolean True if the user can access the entity
		 */
		
		function has_access_to_entity($entity,$user = null) {
			global $CONFIG;
			
			if (!isset($user)) {
				$access_bit = get_access_sql_suffix("e");
			} else {
				$access_bit = get_access_sql_suffix("e",$user->getGUID());
			}
			
			$query = "SELECT guid from {$CONFIG->dbprefix}entities e WHERE e.guid = ".$entity->getGUID();
			$query .= " AND ".$access_bit; // Add access controls
			if (get_data($query)) {
				return true;
			} else {
				return false;
			}			
		}
		
		/**
		 * Returns an array of access permissions that the specified user is allowed to save objects with.
		 * Permissions are of the form ('id' => 'Description')
		 *
		 * @param int $user_id The user's GUID.
		 * @param int $site_id The current site.
		 * @param true|false $flush If this is set to true, this will shun any cached version
		 * @return array List of access permissions=
		 */
		function get_write_access_array($user_id = 0, $site_id = 0, $flush = false) {
			
			global $CONFIG;
			static $access_array;
			
			if ($user_id == 0) $user_id = get_loggedin_userid();
			if (($site_id == 0) && (isset($CONFIG->site_id))) $site_id = $CONFIG->site_id;
			$user_id = (int) $user_id;
			$site_id = (int) $site_id;
			
			if (empty($access_array[$user_id]) || $flush == true) {
				
				$query = "SELECT ag.* FROM {$CONFIG->dbprefix}access_collections ag ";
				$query .= " WHERE (ag.site_guid = {$site_id} OR ag.site_guid = 0)";
				$query .= " AND (ag.owner_guid = {$user_id})";
				$query .= " AND ag.id >= 3";
				
				$tmp_access_array = array(0 => elgg_echo("PRIVATE"), ACCESS_FRIENDS => elgg_echo("access:friends:label"), 1 => elgg_echo("LOGGED_IN"), 2 => elgg_echo("PUBLIC"));
				if ($collections = get_data($query)) {
					foreach($collections as $collection)
						$tmp_access_array[$collection->id] = $collection->name;
				}
				
				$access_array[$user_id] = $tmp_access_array;
				
			} else {
				$tmp_access_array = $access_array[$user_id];
			}
			
			$tmp_access_array = trigger_plugin_hook('access:collections:write','user',array('user_id' => $user_id, 'site_id' => $site_id),$tmp_access_array);
			
			return $tmp_access_array;
			
		}

		/**
		 * Creates a new access control collection owned by the specified user.
		 *
		 * @param string $name The name of the collection.
		 * @param int $owner_guid The GUID of the owner (default: currently logged in user).
		 * @param int $site_guid The GUID of the site (default: current site).
		 * @return int|false Depending on success (the collection ID if successful).
		 */
		function create_access_collection($name, $owner_guid = 0, $site_guid = 0) {
			
			global $CONFIG;
			
			$name = trim($name);
			if (empty($name)) return false;
			
			if ($user_id == 0) $user_id = get_loggedin_userid();
			if (($site_id == 0) && (isset($CONFIG->site_guid))) $site_id = $CONFIG->site_guid;
			$name = sanitise_string($name);
			
			return insert_data("insert into {$CONFIG->dbprefix}access_collections set name = '{$name}', owner_guid = {$owner_guid}, site_guid = {$site_id}");
			
		}
		
		/**
		 * Updates the membership in an access collection.
		 *
		 * @param int $collection_id The ID of the collection.
		 * @param array $members Array of member GUIDs
		 * @return true|false Depending on success
		 */
		function update_access_collection($collection_id, $members) {
			
			global $CONFIG;
			$collection_id = (int) $collection_id;
			
			$collections = get_write_access_array();
			
			if (array_key_exists($collection_id, $collections)) {
			
				delete_data("delete from {$CONFIG->dbprefix}access_collection_membership where access_collection_id = {$collection_id}");
				
				if (is_array($members) && sizeof($members) > 0) {
					foreach($members as $member) {
						$member = (int) $member;
						if (get_user($member))
							insert_data("insert into {$CONFIG->dbprefix}access_collection_membership set access_collection_id = {$collection_id}, user_guid = {$member}");
					}
					return true;
				}
			
			}
			
			return false;
		}
		
		/**
		 * Deletes a specified access collection
		 *
		 * @param int $collection_id The collection ID
		 * @return true|false Depending on success
		 */
		function delete_access_collection($collection_id) {
			
			$collection_id = (int) $collection_id;
			$collections = get_write_access_array();
			if (array_key_exists($collection_id, $collections)) {
				global $CONFIG;
				delete_data("delete from {$CONFIG->dbprefix}access_collection_membership where access_collection_id = {$collection_id}");
				delete_data("delete from {$CONFIG->dbprefix}access_collections where id = {$collection_id}");
				return true;
			} else {
				return false;
			}
			
		}
		
		/**
		 * Get a specified access collection
		 *
		 * @param int $collection_id The collection ID
		 * @return array|false Depending on success
		 */
		function get_access_collection($collection_id) {
    		
    		$collection_id = (int) $collection_id;
    		global $CONFIG;
    		$get_collection = get_data_row("SELECT * FROM {$CONFIG->dbprefix}access_collections WHERE id = {$collection_id}");
    		
    		return $get_collection;
    		
		}
		
		/**
		 * Adds a user to the specified user collection
		 *
		 * @param int $user_guid The GUID of the user to add
		 * @param int $collection_id The ID of the collection to add them to
		 * @return true|false Depending on success
		 */
		function add_user_to_access_collection($user_guid, $collection_id) {
			
			$collection_id = (int) $collection_id;
			$user_guid = (int) $user_guid;
			$collections = get_write_access_array();
			
			if (!($collection = get_access_collection($collection_id)))
				return false;
				
			if ((array_key_exists($collection_id, $collections) || $collection->owner_guid == 0)
					&& $user = get_user($user_guid)) {

				global $CONFIG;
				try {
					insert_data("insert into {$CONFIG->dbprefix}access_collection_membership set access_collection_id = {$collection_id}, user_guid = {$user_guid}");
				} catch (DatabaseException $e) {}
				return true;
				
			}
			
			return false;
			
		}

		/**
		 * Removes a user from an access collection
		 *
		 * @param int $user_guid The user GUID
		 * @param int $collection_id The access collection ID
		 * @return true|false Depending on success
		 */
		function remove_user_from_access_collection($user_guid, $collection_id) {
			
			$collection_id = (int) $collection_id;
			$user_guid = (int) $user_guid;
			$collections = get_write_access_array();
			
			if (!($collection = get_access_collection($collection_id)))
				return false;
			
			if ((array_key_exists($collection_id, $collections) || $collection->owner_guid == 0) && $user = get_user($user_guid)) {
				
				global $CONFIG;
				delete_data("delete from {$CONFIG->dbprefix}access_collection_membership where access_collection_id = {$collection_id} and user_guid = {$user_guid}");
				return true;
				
			}
			
			return false;
			
		}
		
		/**
		 * Get all of a users collections
		 *
		 * @param int $owner_guid The user ID
		 * @return true|false Depending on success
		 */
		function get_user_access_collections($owner_guid) {
			
			$owner_guid = (int) $owner_guid;
			
			global $CONFIG;
			
			$collections = get_data("SELECT * FROM {$CONFIG->dbprefix}access_collections WHERE owner_guid = {$owner_guid}");
			
			return $collections;
			
		}
		
		/**
		 * Get all of members of a friend collection
		 *
		 * @param int $collection The collection's ID
		 * @param true|false $idonly If set to true, will only return the members' IDs (default: false)
		 * @return ElggUser entities if successful, false if not
		 */
		function get_members_of_access_collection($collection, $idonly = false) {
    		
    		$collection = (int)$collection;
    		
    		global $CONFIG;
		
    		if (!$idonly) {
		    	$query = "SELECT e.* FROM {$CONFIG->dbprefix}access_collection_membership m JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.user_guid WHERE m.access_collection_id = {$collection}";	    
				$collection_members = get_data($query, "entity_row_to_elggstar");
    		} else {
    			$query = "SELECT e.guid FROM {$CONFIG->dbprefix}access_collection_membership m JOIN {$CONFIG->dbprefix}entities e ON e.guid = m.user_guid WHERE m.access_collection_id = {$collection}";
    			$collection_members = get_data($query);
    			foreach($collection_members as $key => $val)
    				$collection_members[$key] = $val->guid;
    		}
			
			return $collection_members;
			
		}
		
		/**
		 * Displays a user's access collections, using the friends/collections view
		 *
		 * @param int $owner_guid The GUID of the owning user
		 * @return string A formatted rendition of the collections
		 */
		function elgg_view_access_collections($owner_guid) {
			
			if ($collections = get_user_access_collections($owner_guid)) {
				
				foreach($collections as $key => $collection) {
					$collections[$key]->members = get_members_of_access_collection($collection->id, true);
					$collections[$key]->entities = get_user_friends($owner_guid,"",9999);
				}
				
			}
			
			return elgg_view('friends/collections',array('collections' => $collections));
			
		}
		
		global $init_finished;
		$init_finished = false;
		
	/**
	 * A quick and dirty way to make sure the access permissions have been correctly set up
	 *
	 */
		function access_init() {
			global $init_finished;
			$init_finished = true;
		}
		
	// This function will let us know when 'init' has finished
		register_elgg_event_handler('init','system','access_init',9999);
		
?>