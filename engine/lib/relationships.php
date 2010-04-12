<?php
	/**
	 * Elgg relationships.
	 * Stub containing relationship functions, making import and export easier.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	/**
	 * Relationship class.
	 * 
	 * @author Curverider Ltd
	 * @package Elgg
	 * @subpackage Core
	 */
	class ElggRelationship implements 
		Importable, 
		Exportable,
		Loggable,	// Can events related to this object class be logged
		Iterator,	// Override foreach behaviour
		ArrayAccess // Override for array access
	{
		/**
		 * This contains the site's main properties (id, etc)
		 * @var array
		 */
		protected $attributes;
		
		/**
		 * Construct a new site object, optionally from a given id value or row.
		 *
		 * @param mixed $id
		 */
		function __construct($id = null) 
		{
			$this->attributes = array();
			
			if (!empty($id)) {
				
				if ($id instanceof stdClass)
					$relationship = $id; // Create from db row
				else
					$relationship = get_relationship($id);	
				
				if ($relationship) {
					$objarray = (array) $relationship;
					foreach($objarray as $key => $value) {
						$this->attributes[$key] = $value;
					}
				}
			}
		}
		
		/**
		 * Class member get overloading
		 *
		 * @param string $name
		 * @return mixed
		 */
		protected function __get($name) {
			if (isset($this->attributes[$name])) 
				return $this->attributes[$name];
	
			return null;
		}
		
		/**
		 * Class member set overloading
		 *
		 * @param string $name
		 * @param mixed $value
		 * @return mixed
		 */
		protected function __set($name, $value) {
			$this->attributes[$name] = $value;
			return true;
		}

		/**
		 * Save the relationship
		 *
		 * @return int the relationship id
		 */
		public function save()
		{
			if ($this->id > 0)
			{
				delete_relationship($this->id);
			}

			$this->id = add_entity_relationship($this->guid_one, $this->relationship, $this->guid_two);
			if (!$this->id) throw new IOException(sprintf(elgg_new('IOException:UnableToSaveNew'), get_class()));

			return $this->id;
			
		}
		
		/**
		 * Delete a given relationship.
		 */
		public function delete() 
		{ 
			return delete_relationship($this->id); 
		}
		
		/**
		 * Get a URL for this relationship.
		 *
		 * @return string
		 */
		public function getURL()
		{
			return get_relationship_url($this->id);
		}
	
		// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////
		
		/**
		 * Return an array of fields which can be exported.
		 */
		public function getExportableValues()
		{
			return array(
				'id',
				'guid_one',
				'relationship',
				'guid_two' 
			);
		}
		
		/**
		 * Export this relationship
		 *
		 * @return array
		 */
		public function export()
		{		
			$uuid = get_uuid_from_object($this);
			$relationship = new ODDRelationship(
				guid_to_uuid($this->guid_one),
				$this->relationship,
				guid_to_uuid($this->guid_two)
			);
			
			$relationship->setAttribute('uuid', $uuid);
			
			return $relationship;
		}
		
		// IMPORTABLE INTERFACE ////////////////////////////////////////////////////////////
		
		/**
		 * Import a relationship
		 *
		 * @param array $data
		 * @param int $version
		 * @return ElggRelationship
		 * @throws ImportException
		 */
		public function import(ODD $data)
		{
			if (!($element instanceof ODDRelationship))
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnexpectedODDClass')); 
			
			$uuid_one = $data->getAttribute('uuid1');
			$uuid_two = $data->getAttribute('uuid2'); 	
				
			// See if this entity has already been imported, if so then we need to link to it
			$entity1 = get_entity_from_uuid($uuid_one);
			$entity2 = get_entity_from_uuid($uuid_two);
			if (($entity1) && ($entity2))
			{
				// Set the item ID
				$this->attributes['guid_one'] = $entity1->getGUID();
				$this->attributes['guid_two'] = $entity2->getGUID();
				
				// Map verb to relationship
				//$verb = $data->getAttribute('verb');
				//$relationship = get_relationship_from_verb($verb);
				$relationship = $data->getAttribute('type');
				
				if ($relationship)
				{	
					$this->attributes['relationship'] = $relationship;
					// save
					$result = $this->save(); 
					if (!$result)
						throw new ImportException(sprintf(elgg_echo('ImportException:ProblemSaving'), get_class()));
					
					return $this;
				}
			}
		}
		
		// SYSTEM LOG INTERFACE ////////////////////////////////////////////////////////////
		
		/**
		 * Return an identification for the object for storage in the system log. 
		 * This id must be an integer.
		 * 
		 * @return int 
		 */
		public function getSystemLogID() { return $this->id;	}
		
		/**
		 * Return the class name of the object.
		 */
		public function getClassName() { return get_class($this); }
		
		/**
		 * For a given ID, return the object associated with it.
		 * This is used by the river functionality primarily.
		 * This is useful for checking access permissions etc on objects.
		 */
		public function getObjectFromID($id) { return get_relationship($id); }
		
		/**
		 * Return the GUID of the owner of this object.
		 */
		public function getObjectOwnerGUID() { return $this->owner_guid; }
		
		/**
		 * Return a type of the object - eg. object, group, user, relationship, metadata, annotation etc
		 */
		public function getType() { return 'relationship'; }
		
		/**
		 * Return a subtype. For metadata & annotations this is the 'name' and for relationship this is the relationship type.
		 */
		public function getSubtype() { return $this->relationship; } 
		
		// ITERATOR INTERFACE //////////////////////////////////////////////////////////////
		/*
		 * This lets an entity's attributes be displayed using foreach as a normal array.
		 * Example: http://www.sitepoint.com/print/php5-standard-library
		 */
		
		private $valid = FALSE; 
		
   		function rewind() 
   		{ 
   			$this->valid = (FALSE !== reset($this->attributes));  
   		}
   
   		function current() 
   		{ 
   			return current($this->attributes); 
   		}
		
   		function key() 
   		{ 
   			return key($this->attributes); 
   		}
		
   		function next() 
   		{
   			$this->valid = (FALSE !== next($this->attributes));  
   		}
   		
   		function valid() 
   		{ 
   			return $this->valid;  
   		}
	
   		// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////
		/*
		 * This lets an entity's attributes be accessed like an associative array.
		 * Example: http://www.sitepoint.com/print/php5-standard-library
		 */

		function offsetSet($key, $value)
		{
   			if ( array_key_exists($key, $this->attributes) ) {
     			$this->attributes[$key] = $value;
   			}
 		} 
 		
 		function offsetGet($key) 
 		{
   			if ( array_key_exists($key, $this->attributes) ) {
     			return $this->attributes[$key];
   			}
 		} 
 		
 		function offsetUnset($key) 
 		{
   			if ( array_key_exists($key, $this->attributes) ) {
     			$this->attributes[$key] = ""; // Full unsetting is dangerious for our objects
   			}
 		} 
 		
 		function offsetExists($offset) 
 		{
   			return array_key_exists($offset, $this->attributes);
 		} 
	}
	
	
	/**
	 * Convert a database row to a new ElggRelationship
	 *
	 * @param stdClass $row
	 * @return stdClass or ElggMetadata
	 */
	function row_to_elggrelationship($row) 
	{
		if (!($row instanceof stdClass))
			return $row;
			
		return new ElggRelationship($row);
	}
	
	/**
	 * Return a relationship.
	 *
	 * @param int $id
	 */
	function get_relationship($id)
	{
		global $CONFIG;
		
		$id = (int)$id;
		
		return row_to_elggrelationship(get_data_row("SELECT * from {$CONFIG->dbprefix}entity_relationships where id=$id"));
	}
	
	/**
	 * Delete a specific relationship.
	 *
	 * @param int $id
	 */
	function delete_relationship($id)
	{
		global $CONFIG;
		
		$id = (int)$id;
		
		$result = delete_data("delete from {$CONFIG->dbprefix}entity_relationships where id=$id");
		
		return $result;
	}
	
	/**
	 * Define an arbitrary relationship between two entities.
	 * This relationship could be a friendship, a group membership or a site membership.
	 * 
	 * This function lets you make the statement "$guid_one has $relationship with $guid_two".
	 * 
	 * @param int $guid_one
	 * @param string $relationship 
	 * @param int $guid_two
	 */
	function add_entity_relationship($guid_one, $relationship, $guid_two)
	{
		global $CONFIG;
		
		$guid_one = (int)$guid_one;
		$relationship = sanitise_string($relationship);
		$guid_two = (int)$guid_two;
			
		// Check for duplicates
		if (check_entity_relationship($guid_one, $relationship, $guid_two))
			return false;
		
		$result = insert_data("INSERT into {$CONFIG->dbprefix}entity_relationships (guid_one, relationship, guid_two) values ($guid_one, '$relationship', $guid_two)");
		
		if ($result!==false) {
			$obj = get_relationship($result);
			if (trigger_elgg_event('create', $relationship, $obj)) {
				return true;
			} else {
				delete_relationship($result);
			}
		}
				
		return false;
	}
	
	/**
	 * Determine whether or not a relationship between two entities exists and returns the relationship object if it does
	 *
	 * @param int $guid_one The GUID of the entity "owning" the relationship
	 * @param string $relationship The type of relationship
	 * @param int $guid_two The GUID of the entity the relationship is with
	 * @return object|false Depending on success
	 */
	function check_entity_relationship($guid_one, $relationship, $guid_two)
	{
		global $CONFIG;
		
		$guid_one = (int)$guid_one;
		$relationship = sanitise_string($relationship);
		$guid_two = (int)$guid_two;
			
		if ($row = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entity_relationships WHERE guid_one=$guid_one AND relationship='$relationship' AND guid_two=$guid_two limit 1")) {
			return $row;
		}
		return false;
	}

	/**
	 * Remove an arbitrary relationship between two entities.
	 * 
	 * @param int $guid_one
	 * @param string $relationship 
	 * @param int $guid_two
	 */
	function remove_entity_relationship($guid_one, $relationship, $guid_two)
	{
		global $CONFIG;
		
		$guid_one = (int)$guid_one;
		$relationship = sanitise_string($relationship);
		$guid_two = (int)$guid_two;
		
		$obj = check_entity_relationship($guid_one, $relationship, $guid_two);
		if ($obj == false) return false;
		
		if (trigger_elgg_event('delete', $relationship, $obj)) {
			return delete_data("DELETE from {$CONFIG->dbprefix}entity_relationships where guid_one=$guid_one and relationship='$relationship' and guid_two=$guid_two");
		} else {
			return false;
		}
	}

	/**
	 * Removes all arbitrary relationships originating from a particular entity
	 *
	 * @param int $guid_one The GUID of the entity 
	 * @param string $relationship The name of the relationship (optionally)
	 * @param true|false $inverse Whether we're deleting inverse relationships (default false)
	 * @param string $type The type of entity to limit this relationship delete to (defaults to all)
	 * @return true|false Depending on success
	 */
	function remove_entity_relationships($guid_one, $relationship = "", $inverse = false, $type = '') {
		
		global $CONFIG;
		
		$guid_one = (int) $guid_one;
		
		if (!empty($relationship)) {
			$relationship = sanitise_string($relationship);
			$where = "and er.relationship='$relationship'";
		} else {
			$where = "";
		}
		
		if (!empty($type)) {
			$type = sanitise_string($type);
			if (!$inverse) {
				$join = " join {$CONFIG->dbprefix}entities e on e.guid = er.guid_two ";
			} else {
				$join = " join {$CONFIG->dbprefix}entities e on e.guid = er.guid_one ";
				$where .= " and ";
			}
			$where .= " and e.type = '{$type}' ";
		} else {
			$join = "";
		}
		
		if (!$inverse) {
			$sql = "DELETE er from {$CONFIG->dbprefix}entity_relationships as er {$join} where guid_one={$guid_one} {$where}";
			return delete_data($sql);
		} else {
			$sql = "DELETE er from {$CONFIG->dbprefix}entity_relationships as er {$join} where guid_two={$guid_one} {$where}";
			return delete_data($sql);
		}
		
	}

	/**
	 * Get all the relationships for a given guid.
	 * 
	 * @param int $guid
	 */
	function get_entity_relationships($guid)
	{
		global $CONFIG;
		
		$guid = (int)$guid;
		
		$query = "SELECT * from {$CONFIG->dbprefix}entity_relationships where guid_one=$guid";
		
		return get_data($query, "row_to_elggrelationship");
	}
	
	/**
	 * Return entities matching a given query joining against a relationship.
	 * 
	 * @param string $relationship The relationship eg "friends_of"
	 * @param int $relationship_guid The guid of the entity to use query
	 * @param bool $inverse_relationship Reverse the normal function of the query to instead say "give me all entities for whome $relationship_guid is a $relationship of"
	 * @param string $type 
	 * @param string $subtype
	 * @param int $owner_guid
	 * @param string $order_by
	 * @param int $limit
	 * @param int $offset
	 * @param boolean $count Set to true if you want to count the number of entities instead (default false)
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
	 * @return array|int|false An array of entities, or the number of entities, or false on failure
	 */
	function get_entities_from_relationship($relationship, $relationship_guid, $inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0)
	{
		global $CONFIG;
		
		$relationship = sanitise_string($relationship);
		$relationship_guid = (int)$relationship_guid;
		$inverse_relationship = (bool)$inverse_relationship;
		$type = sanitise_string($type);
		$subtype = get_subtype_id($type, $subtype);
		$owner_guid = (int)$owner_guid;
		if ($order_by == "") $order_by = "time_created desc";
		$order_by = sanitise_string($order_by);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$site_guid = (int) $site_guid;
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
		
		//$access = get_access_list();
		
		$where = array();
		
		if ($relationship!="")
			$where[] = "r.relationship='$relationship'";
		if ($relationship_guid)
			$where[] = ($inverse_relationship ? "r.guid_two='$relationship_guid'" : "r.guid_one='$relationship_guid'");
		if ($type != "")
			$where[] = "e.type='$type'";
		if ($subtype)
			$where[] = "e.subtype=$subtype";
		if ($owner_guid != "")
			$where[] = "e.container_guid='$owner_guid'";
		if ($site_guid > 0)
			$where[] = "e.site_guid = {$site_guid}";
		
		// Select what we're joining based on the options
		$joinon = "e.guid = r.guid_one";
		if (!$inverse_relationship)
			$joinon = "e.guid = r.guid_two";	
			
		if ($count) {
			$query = "SELECT count(distinct e.guid) as total ";
		} else {
			$query = "SELECT distinct e.* ";
		}
		$query .= " from {$CONFIG->dbprefix}entity_relationships r JOIN {$CONFIG->dbprefix}entities e on $joinon where ";
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("e"); // Add access controls
		if (!$count) {
			$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
			return get_data($query, "entity_row_to_elggstar");
		} else {
			if ($count = get_data_row($query)) {
				return $count->total;
			}
		}
		return false;
		
	}

	/**
	 * Returns a viewable list of entities by relationship
	 *
	 * @see elgg_view_entity_list
	 * 
	 * @param string $relationship The relationship eg "friends_of"
	 * @param int $relationship_guid The guid of the entity to use query
	 * @param bool $inverse_relationship Reverse the normal function of the query to instead say "give me all entities for whome $relationship_guid is a $relationship of"
	 * @param string $type The type of entity (eg 'object')
	 * @param string $subtype The entity subtype
	 * @param int $owner_guid The owner (default: all)
	 * @param int $limit The number of entities to display on a page
	 * @param true|false $fullview Whether or not to display the full view (default: true)
	 * @param true|false $viewtypetoggle Whether or not to allow gallery view 
	 * @param true|false $pagination Whether to display pagination (default: true)
	 * @return string The viewable list of entities
	 */
	function list_entities_from_relationship($relationship, $relationship_guid, $inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = false, $pagination = true) {
		
		$limit = (int) $limit;
		$offset = (int) get_input('offset');
		$count = get_entities_from_relationship($relationship, $relationship_guid, $inverse_relationship, $type, $subtype, $owner_guid, "", $limit, $offset, true);
		$entities = get_entities_from_relationship($relationship, $relationship_guid, $inverse_relationship, $type, $subtype, $owner_guid, "", $limit, $offset);

		return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle, $pagination);
		
	}

	/**
	 * Gets the number of entities by a the number of entities related to them in a particular way.
	 * This is a good way to get out the users with the most friends, or the groups with the most members.
	 *
	 * @param string $relationship The relationship eg "friends_of"
	 * @param bool $inverse_relationship Reverse the normal function of the query to instead say "give me all entities for whome $relationship_guid is a $relationship of" (default: true)
	 * @param string $type The type of entity (default: all)
	 * @param string $subtype The entity subtype (default: all)
	 * @param int $owner_guid The owner of the entities (default: none)
	 * @param int $limit
	 * @param int $offset
	 * @param boolean $count Set to true if you want to count the number of entities instead (default false)
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
	 * @return array|int|false An array of entities, or the number of entities, or false on failure
	 */
	
	function get_entities_by_relationship_count($relationship, $inverse_relationship = true, $type = "", $subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $count = false, $site_guid = 0) {
		
		global $CONFIG;
		
		$relationship = sanitise_string($relationship);
		$inverse_relationship = (bool)$inverse_relationship;
		$type = sanitise_string($type);
		$subtype = get_subtype_id($type, $subtype);
		$owner_guid = (int)$owner_guid;
		$order_by = sanitise_string($order_by);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$site_guid = (int) $site_guid;
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
		
		//$access = get_access_list();
		
		$where = array();
		
		if ($relationship!="")
			$where[] = "r.relationship='$relationship'";
		if ($inverse_relationship) {
			$on = 'e.guid = r.guid_two';
		} else {
			$on = 'e.guid = r.guid_one';
		}
		if ($type != "")
			$where[] = "e.type='$type'";
		if ($subtype)
			$where[] = "e.subtype=$subtype";
		if ($owner_guid != "")
			$where[] = "e.container_guid='$owner_guid'";
		if ($site_guid > 0)
			$where[] = "e.site_guid = {$site_guid}";
		
		if ($count) {
			$query = "SELECT count(distinct e.guid) as total ";
		} else {
			$query = "SELECT e.*, count(e.guid) as total ";
		}
		
		$query .= " from {$CONFIG->dbprefix}entity_relationships r JOIN {$CONFIG->dbprefix}entities e on {$on} where ";
		
		if (!empty($where))
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix("e"); // Add access controls
		
		if (!$count) {
			$query .= " group by e.guid ";
			$query .= " order by total desc limit {$offset}, {$limit}"; // Add order and limit
			return get_data($query, "entity_row_to_elggstar");
		} else {
			if ($count = get_data_row($query)) {
				return $count->total;
			}
		}
		
		return false;
			
	}
	
	/**
	 * Displays a human-readable list of entities
	 * 
	 * @param string $relationship The relationship eg "friends_of"
	 * @param bool $inverse_relationship Reverse the normal function of the query to instead say "give me all entities for whome $relationship_guid is a $relationship of" (default: true)
	 * @param string $type The type of entity (eg 'object')
	 * @param string $subtype The entity subtype
	 * @param int $owner_guid The owner (default: all)
	 * @param int $limit The number of entities to display on a page
	 * @param true|false $fullview Whether or not to display the full view (default: true)
	 * @param true|false $viewtypetoggle Whether or not to allow gallery view 
	 * @param true|false $pagination Whether to display pagination (default: true)
	 * @return string The viewable list of entities
	 */
	
	function list_entities_by_relationship_count($relationship, $inverse_relationship = true, $type = "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = false, $pagination = true) {
		
		$limit = (int) $limit;
		$offset = (int) get_input('offset');
		$count = get_entities_by_relationship_count($relationship,$inverse_relationship,$type,$subtype,$owner_guid,0,0,true);
		$entities = get_entities_by_relationship_count($relationship,$inverse_relationship,$type,$subtype,$owner_guid,$limit,$offset);

		return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle, $pagination);
		
	}
	
	/**
	 * Sets the URL handler for a particular relationship type
	 *
	 * @param string $function_name The function to register
	 * @param string $relationship_type The relationship type.
	 * @return true|false Depending on success
	 */
	function register_relationship_url_handler($function_name, $relationship_type = "all") {
		global $CONFIG;
		
		if (!is_callable($function_name)) return false;
		
		if (!isset($CONFIG->relationship_url_handler)) {
			$CONFIG->relationship_url_handler = array();
		}
		
		$CONFIG->relationship_url_handler[$relationship_type] = $function_name;
		
		return true;
		
	}
	
	/**
	 * Get the url for a given relationship.
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function get_relationship_url($id)
	{
		global $CONFIG;
		
		$id = (int)$id;
		
		if ($relationship = get_relationship($id))
		{
			$view = elgg_get_viewtype(); 
				
			$guid = $relationship->guid_one;
			$type = $relationship->relationship;
			
			$url = "";
			
			$function = "";
			if (isset($CONFIG->relationship_url_handler[$type]))
				$function = $CONFIG->relationship_url_handler[$type];
			if (isset($CONFIG->relationship_url_handler['all']))
				$function = $CONFIG->relationship_url_handler['all'];
				
			if (is_callable($function)) {
				$url = $function($relationship);
			}
			
			if ($url == "") {
				
				$nameid = $relationship->id;
				
				$url = $CONFIG->wwwroot  . "export/$view/$guid/relationship/$nameid/";
			} 
			
			return $url;
			
		}
		
		return false;
	}
	
	/**** HELPER FUNCTIONS FOR RELATIONSHIPS OF TYPE 'ATTACHED' ****/
	
	 /**
     * Function to determine if the object trying to attach to other, has already done so
     * @param int $guid_one This is the target object
     * @param int $guid_two This is the object trying to attach to $guid_one
     * @return true | false
     **/
     
     function already_attached($guid_one, $guid_two){
    
         if($attached = check_entity_relationship($guid_one, "attached", $guid_two)){
             return true;
         }else{
             return false;
         }
     }
     
     /**
     * Function to get all objects attached to a particular object
     * @param int $guid
     * @param string $type - the type of object to return e.g. 'file', 'friend_of' etc
     * @return an array of objects
    **/
         
        function get_attachments($guid, $type=""){
            
            $attached = get_entities_from_relationship("attached", $guid, $inverse_relationship = false, $type, $subtype = "", $owner_guid = 0, $order_by = "time_created desc", $limit = 10, $offset = 0, $count = false, $site_guid = 0);
            return $attached;
           
        }
        
     /**
     * Function to remove a particular attachment between two objects
     * @param int $guid_one This is the target object
     * @param int $guid_two This is the object to remove from $guid_one
     * @return a view 
    **/
         
        function remove_attachment($guid_one, $guid_two){
            
            if(already_attached($guid_one, $guid_two))
                remove_entity_relationship($guid_one, "attached", $guid_two);
           
        }
        
        
     
     /**
     * Function to start the process of attaching one object to another
     * @param int $guid_one This is the target object
     * @param int $guid_two This is the object trying to attach to $guid_one
     * @return a view 
    **/
         
        function make_attachment($guid_one, $guid_two){
            
            if(!(already_attached($guid_one, $guid_two)))
                if(add_entity_relationship($guid_one, "attached", $guid_two))
                    return true;
           
        }
	
	/**
	 *  Handler called by trigger_plugin_hook on the "import" event.
	 */
	function import_relationship_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		$element = $params['element'];
		
		$tmp = NULL;
		
		if ($element instanceof ODDRelationship)
		{
			$tmp = new ElggRelationship();
			$tmp->import($element);
			
			return $tmp;
		}
	}
	
	/**
	 *  Handler called by trigger_plugin_hook on the "export" event.
	 */
	function export_relationship_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		
		// Sanity check values
		if ((!is_array($params)) && (!isset($params['guid'])))
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:GUIDNotForExport'));
			
		if (!is_array($returnvalue))
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonArrayReturnValue'));
			
		$guid = (int)$params['guid'];
		
		$result = get_entity_relationships($guid);
		
		if ($result)
		{
			foreach ($result as $r)
				$returnvalue[] = $r->export();
		}
		
		return $returnvalue;
	}

    /**
     * An event listener which will notify users based on certain events.
     *
     * @param unknown_type $event
     * @param unknown_type $object_type
     * @param unknown_type $object
     */
	function relationship_notification_hook($event, $object_type, $object)
	{
		global $CONFIG;
		
		if (
			($object instanceof ElggRelationship) &&
			($event == 'create') &&
			($object_type == 'friend')
		)
		{
			$user_one = get_entity($object->guid_one);
			$user_two = get_entity($object->guid_two);
			
			// Notify target user
			return notify_user($object->guid_two, $object->guid_one, sprintf(elgg_echo('friend:newfriend:subject'), $user_one->name), 
				sprintf(elgg_echo("friend:newfriend:body"), $user_one->name, $CONFIG->site->url . "pg/profile/" . $user_one->username)
			); 
		}
	}
	
	/** Register the import hook */
	register_plugin_hook("import", "all", "import_relationship_plugin_hook", 3);
	
	/** Register the hook, ensuring entities are serialised first */
	register_plugin_hook("export", "all", "export_relationship_plugin_hook", 3);
	
	/** Register event to listen to some events **/
	register_elgg_event_handler('create','friend','relationship_notification_hook');
?>