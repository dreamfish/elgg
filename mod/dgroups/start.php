<?php
	/**
	 * Elgg dgroups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the dgroups plugin.
	 * Register actions, set up menus
	 */
	function dgroups_init()
	{
    	
    	global $CONFIG;
		
		// Set up the menu for logged in users
		if (isloggedin()) 
		{
			add_menu(elgg_echo('dgroups'), $CONFIG->wwwroot . "pg/dgroups/world/");
			//add_menu(elgg_echo('dgroups:alldiscussion'),$CONFIG->wwwroot."mod/dgroups/discussions.php");
		}
		else
		{
			add_menu(elgg_echo('dgroups'), $CONFIG->wwwroot . "pg/dgroups/world/");
		}
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('dgroups','dgroups_page_handler');
		
		// Register a URL handler for dgroups and forum topics
		register_entity_url_handler('dgroups_url','group','dgroup');
		register_entity_url_handler('dgroups_dgroupforumtopic_url','object','dgroupforumtopic');
		
		// Register an icon handler for dgroups
		register_page_handler('dgroupicon','dgroups_icon_handler');
		
		// Register some actions
		register_action("dgroups/edit",false, $CONFIG->pluginspath . "dgroups/actions/edit.php");
		register_action("dgroups/delete",false, $CONFIG->pluginspath . "dgroups/actions/delete.php");
		register_action("dgroups/join",false, $CONFIG->pluginspath . "dgroups/actions/join.php");
		register_action("dgroups/leave",false, $CONFIG->pluginspath . "dgroups/actions/leave.php");
		register_action("dgroups/joinrequest",false, $CONFIG->pluginspath . "dgroups/actions/joinrequest.php");
		register_action("dgroups/killrequest",false,$CONFIG->pluginspath . "dgroups/actions/dgroupskillrequest.php");
		register_action("dgroups/addtodgroup",false, $CONFIG->pluginspath . "dgroups/actions/addtodgroup.php");
		register_action("dgroups/invite",false, $CONFIG->pluginspath . "dgroups/actions/invite.php");
		
		// Use dgroup widgets
		use_widgets('dgroups');
		
		// Add a page owner handler
		add_page_owner_handler('dgroups_page_owner_handler');
		
		// Add some widgets
		add_widget_type('a_users_dgroups',elgg_echo('dgroups:widget:membership'), elgg_echo('dgroups:widgets:description'));
		
		
		//extend some views
		extend_view('profile/icon','dgroups/icon');
		extend_view('css','dgroups/css');
		
		// Write access permissions
		register_plugin_hook('access:collections:write', 'all', 'dgroups_write_acl_plugin_hook');
		
		// Notification hooks
		if (is_callable('register_notification_object'))
			register_notification_object('object', 'dgroupforumtopic', elgg_echo('dgroupforumtopic:new'));
		register_plugin_hook('object:notifications','object','dgroup_object_notifications_intercept');
		
		// Listen to notification events and supply a more useful message
		register_plugin_hook('notify:entity:message', 'object', 'dgroupforumtopic_notify_message');
		
		// add the forum tool option
		
		
		// Now override icons
		register_plugin_hook('entity:icon:url', 'group', 'dgroups_dgroupicon_hook');
	}
	
	/**
	 * Event handler for dgroup forum posts
	 *
	 */
	function dgroup_object_notifications($event, $object_type, $object) {
		
		static $flag;
		if (!isset($flag)) $flag = 0;
		
		if (is_callable('object_notifications'))
		if ($object instanceof ElggObject) {
			if ($object->getSubtype() == 'dgroupforumtopic') {
				//if ($object->countAnnotations('dgroup_topic_post') > 0) {
				if ($flag == 0) {
					$flag = 1;
					object_notifications($event, $object_type, $object);
				}
				//}
			}
		}
		
	}
	
	/**
	 * Intercepts the notification on dgroup topic creation and prevents a notification from going out
	 * (because one will be sent on the annotation)
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 * @return unknown
	 */
		function dgroup_object_notifications_intercept($hook, $entity_type, $returnvalue, $params) {
			if (isset($params)) {
				if ($params['event'] == 'create' && $params['object'] instanceof ElggObject) {
					if ($params['object']->getSubtype() == 'dgroupforumtopic') {
						return true;
					}
				}
			}
			return null;
		}
	
		/**
		 * Returns a more meaningful message
		 *
		 * @param unknown_type $hook
		 * @param unknown_type $entity_type
		 * @param unknown_type $returnvalue
		 * @param unknown_type $params
		 */
		function dgroupforumtopic_notify_message($hook, $entity_type, $returnvalue, $params)
		{
			$entity = $params['entity'];
			$to_entity = $params['to_entity'];
			$method = $params['method'];
			if (($entity instanceof ElggEntity) && ($to_entity->guid != $entity->owner_guid) && ($entity->getSubtype() == 'dgroupforumtopic'))
			{

				$descr = $entity->description;
				$title = $entity->title;
				global $CONFIG;
				$url = $entity->getURL();

				$msg = get_input('topicmessage');
				if (empty($msg)) $msg = get_input('topic_post');
				if (!empty($msg)) $msg = $msg . "\n\n"; else $msg = '';
				
				$owner = get_entity($entity->container_guid);
				if ($method == 'sms') {
					return elgg_echo("dgroupforumtopic:new") . ': ' . $url . " ({$owner->name}: {$title})";
				} else {
					return $_SESSION['user']->username . ' ' . elgg_echo("dgroups:viadgroups") . ': ' . $title . "\n\n" . $msg . "\n\n" . $entity->getURL();
				}

			}
			return null;
		}
	
	/**
	 * This function loads a set of default fields into the profile, then triggers a hook letting other plugins to edit
	 * add and delete fields.
	 *
	 * Note: This is a secondary system:init call and is run at a super low priority to guarantee that it is called after all
	 * other plugins have initialised.
	 */
	function dgroups_fields_setup()
	{
		global $CONFIG;
		
		$profile_defaults = array(
		
			'name' => 'text',
			'description' => 'longtext',
			'briefdescription' => 'text',
			'interests' => 'tags',
			'website' => 'url',
							   
		);
		
		$CONFIG->dgroup = trigger_plugin_hook('profile:fields', 'dgroup', NULL, $profile_defaults);
	}
	
	/**
	 * Sets up submenus for the dgroups system.  Triggered on pagesetup.
	 *
	 */
	function dgroups_submenus() {
		
		global $CONFIG;
		
		// Get the page owner entity
			$page_owner = page_owner_entity();
		
		// Submenu items for all dgroup pages
			if ($page_owner instanceof ElggGroup && get_context() == 'dgroups') {
				add_submenu_item('Send Message to Owner',$CONFIG->wwwroot . "mod/messages/send.php?send_to={$page_owner->owner_guid}&title={$page_owner->name}", '1dgroupsactions');
				if (isloggedin()) {
					if ($page_owner->canEdit()) {
						add_submenu_item(elgg_echo('dgroups:edit'),$CONFIG->wwwroot . "mod/dgroups/edit.php?dgroup_guid=" . $page_owner->getGUID(), '1dgroupsactions');
						add_submenu_item(elgg_echo('dgroups:invite'),$CONFIG->wwwroot . "mod/dgroups/invite.php?dgroup_guid={$page_owner->getGUID()}", '1dgroupsactions');
						if (!$page_owner->isPublicMembership())
							add_submenu_item(elgg_echo('dgroups:membershiprequests'),$CONFIG->wwwroot . "mod/dgroups/membershipreq.php?dgroup_guid={$page_owner->getGUID()}", '1dgroupsactions');
					}
					if ($page_owner->isMember($_SESSION['user'])) {
						if ($page_owner->getOwner() != $_SESSION['guid'])
							add_submenu_item(elgg_echo('dgroups:leave'), $CONFIG->wwwroot . "action/dgroups/leave?dgroup_guid=" . $page_owner->getGUID(), '1dgroupsactions');
					} else {
						if ($page_owner->isPublicMembership())
						{
							add_submenu_item(elgg_echo('dgroups:join'),$CONFIG->wwwroot . "action/dgroups/join?dgroup_guid={$page_owner->getGUID()}", '1dgroupsactions');		
						}
						else
						{
							add_submenu_item(elgg_echo('dgroups:joinrequest'),$CONFIG->wwwroot . "action/dgroups/joinrequest?dgroup_guid={$page_owner->getGUID()}", '1dgroupsactions');		
						}
					}
				}
				
				if($page_owner->forum_enable != "no"){ 
				    add_submenu_item(elgg_echo('dgroups:forum'),$CONFIG->wwwroot . "pg/dgroups/forum/{$page_owner->getGUID()}/", '1dgroupslinks');
			    }
					
			}
		
		// Add submenu options
			if (get_context() == 'dgroups' && !($page_owner instanceof ElggGroup)) {
				if (isloggedin()) {
					add_submenu_item(elgg_echo('dgroups:new'), $CONFIG->wwwroot."pg/dgroups/new/", '1dgroupslinks');
					add_submenu_item(elgg_echo('dgroups:owned'), $CONFIG->wwwroot . "pg/dgroups/owned/" . $_SESSION['user']->username, '1dgroupslinks');
					add_submenu_item(elgg_echo('dgroups:yours'), $CONFIG->wwwroot . "pg/dgroups/member/" . $_SESSION['user']->username, '1dgroupslinks');
				}
				add_submenu_item(elgg_echo('dgroups:all'), $CONFIG->wwwroot . "pg/dgroups/world/", '1dgroupslinks');
			}
		
	}
	
	/**
	 * Set a page owner handler.
	 *
	 */
	function dgroups_page_owner_handler()
	{
		$dgroup_guid = get_input('dgroup_guid');
		if ($dgroup_guid)
		{
			$dgroup = get_entity($dgroup_guid);
			if ($dgroup instanceof ElggGroup)
				return $dgroup->owner_guid;
		}
		
		return false;
	}
	
	/**
	 * dgroup page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function dgroups_page_handler($page) 
	{
		global $CONFIG;
		
		
		if (isset($page[0]))
		{
			// See what context we're using
			switch($page[0])
			{
				case "new" :
					include($CONFIG->pluginspath . "dgroups/new.php");
          		break;
    			case "world":  
					set_context('dgroups');
					set_page_owner(0);
   					include($CONFIG->pluginspath . "dgroups/all.php");
          		break;
          		case "forum":
          		    set_input('dgroup_guid', $page[1]);
   					include($CONFIG->pluginspath . "dgroups/forum.php");	
          		break;
    			case "owned" :
    				// Owned by a user
    				if (isset($page[1]))
    					set_input('username',$page[1]);
    					
    				include($CONFIG->pluginspath . "dgroups/index.php");	
    			break;
    			case "member" :
    				// User is a member of
    				if (isset($page[1]))
    					set_input('username',$page[1]);
    					
    				include($CONFIG->pluginspath . "dgroups/membership.php");	
    			break;    				
    			default:
    				set_input('dgroup_guid', $page[0]);
    				include($CONFIG->pluginspath . "dgroups/dgroupprofile.php");
    			break;
			}
		}
		
	}
	
	/**
	 * Handle dgroup icons.
	 *
	 * @param unknown_type $page
	 */
	function dgroups_icon_handler($page) {
			
		global $CONFIG;
		
		// The username should be the file we're getting
		if (isset($page[0])) {
			set_input('dgroup_guid',$page[0]);
		}
		if (isset($page[1])) {
			set_input('size',$page[1]);
		}
		// Include the standard profile index
		include($CONFIG->pluginspath . "dgroups/graphics/icon.php");
		
	}
	
	/**
	 * Populates the ->getUrl() method for dgroup objects
	 *
	 * @param ElggEntity $entity File entity
	 * @return string File URL
	 */
	function dgroups_url($entity) {
		
		global $CONFIG;
		
		$title = friendly_title($entity->name);
		
		return $CONFIG->url . "pg/dgroups/{$entity->guid}/$title/";
		
	}
	
	function dgroups_dgroupforumtopic_url($entity) {
		
		global $CONFIG;
		return $CONFIG->url . 'mod/dgroups/topicposts.php?topic='. $entity->guid .'&dgroup_guid=' . $entity->container_guid;
		
	}
	
	/**
	 * dgroups created, so add users to access lists.
	 */
	function dgroups_create_event_listener($event, $object_type, $object)
	{
		//if (($event == 'create') && ($object_type == 'dgroup') && ($object instanceof ElggGroup))
		//{
			$dgroup_id = create_access_collection(elgg_echo('dgroups:dgroup') . ": " . $object->name);
			if ($dgroup_id)
			{
				 $object->dgroup_acl = $dgroup_id;
			}
			else
				return false;
		//}
		
		return true;
	}
	
	/**
	 * Hook to listen to read access control requests and return all the dgroups you are a member of.
	 */
	function dgroups_read_acl_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		
		//error_log("READ: " . var_export($returnvalue));
		$user = $_SESSION['user'];
		if ($user)
		{
			$membership = get_users_membership($user->guid);
			
			if ($membership)
			{					
				foreach ($membership as $dgroup)
					$returnvalue[$user->guid][$dgroup->dgroup_acl] = elgg_echo('dgroups:dgroup') . ": " . $dgroup->name; 
				return $returnvalue;
			}
		}
	}
	
	/**
	 * Return the write access for the current dgroup if the user has write access to it.
	 */
	function dgroups_write_acl_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		$page_owner = page_owner_entity();
		
		if ($page_owner instanceof ElggGroup)
		{
			if (can_write_to_container())
			{
				$returnvalue[$page_owner->dgroup_acl] = elgg_echo('dgroups:dgroup') . ": " . $page_owner->name;
			
				return $returnvalue;
			}
		}
	}
	
	/**
	 * dgroups deleted, so remove access lists.
	 */
	function dgroups_delete_event_listener($event, $object_type, $object)
	{
		delete_access_collection($object->access_id);
		
		return true;
	}
	
	/**
	 * Listens to a dgroup join event and adds a user to the dgroup's access control
	 *
	 */
	function dgroups_user_join_event_listener($event, $object_type, $object) {
		
		$dgroup = $object['dgroup'];
		$user = $object['user'];
		$acl = $dgroup->dgroup_acl;

		add_user_to_access_collection($user->guid, $acl);
		
		return true;
		
	}
	
	/**
	 * Listens to a dgroup leave event and removes a user from the dgroup's access control
	 *
	 */
	function dgroups_user_leave_event_listener($event, $object_type, $object) {
		
		$dgroup = $object['dgroup'];
		$user = $object['user'];
		$acl = $dgroup->dgroup_acl;

		remove_user_from_access_collection($user->guid, $acl);
		
		return true;
		
	}

	/**
	 * This hooks into the getIcon API and provides nice user icons for users where possible.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 * @return unknown
	 */
	function dgroups_dgroupicon_hook($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		
		if ((!$returnvalue) && ($hook == 'entity:icon:url') && ($params['entity'] instanceof ElggGroup))
		{
			$entity = $params['entity'];
			$type = $entity->type;
			$viewtype = $params['viewtype'];
			$size = $params['size'];
			
			if ($icontime = $entity->icontime) {
				$icontime = "{$icontime}";
			} else {
				$icontime = "default";
			}
			
			$filehandler = new ElggFile();
			$filehandler->owner_guid = $entity->owner_guid;
			$filehandler->setFilename("dgroups/" . $entity->guid . $size . ".jpg");
			
			if ($filehandler->exists()) {
				$url = $CONFIG->url . "pg/dgroupicon/{$entity->guid}/$size/$icontime.jpg";
			
				return $url;
			}
		}
	}
	
	/**
	 * A simple function to see who can edit a dgroup discussion post
	 * @param the comment $entity
	 * @param user who owns the dgroup $dgroup_owner
	 * @return boolean
	 */
	function dgroups_can_edit_discussion($entity, $dgroup_owner)
	{
		
		//logged in user
		$user = $_SESSION['user']->guid;
		
		if (($entity->owner_guid == $user) || $dgroup_owner == $user || isadminloggedin()) {
        	return true;
    	}else{
			return false;
		}
		
	}
	
	/**
	 * Overrides topic post getURL() value.
	 *
	 */
	function dgroup_topicpost_url($annotation) {
		if ($parent = get_entity($annotation->entity_guid)) {
			global $CONFIG;
			return $CONFIG->wwwroot . 'mod/dgroups/topicposts.php?topic='.$parent->guid.'&amp;dgroup_guid='.$parent->container_guid.'#' . $annotation->id;
		}
	}
	
	register_extender_url_handler('dgroup_topicpost_url','annotation', 'dgroup_topic_post');
	
	// Register a handler for create dgroups
	register_elgg_event_handler('create', 'dgroup', 'dgroups_create_event_listener');

	// Register a handler for delete dgroups
	register_elgg_event_handler('delete', 'dgroup', 'dgroups_delete_event_listener');
	
	// Make sure the dgroups initialisation function is called on initialisation
	register_elgg_event_handler('init','system','dgroups_init');
	register_elgg_event_handler('init','system','dgroups_fields_setup', 10000); // Ensure this runs after other plugins
	register_elgg_event_handler('join','dgroup','dgroups_user_join_event_listener');
	register_elgg_event_handler('leave','dgroup','dgroups_user_leave_event_listener');
	register_elgg_event_handler('pagesetup','system','dgroups_submenus');
	register_elgg_event_handler('annotate','all','dgroup_object_notifications');
	
	// Register actions
    global $CONFIG;
	register_action("dgroups/addtopic",false,$CONFIG->pluginspath . "dgroups/actions/forums/addtopic.php");
	register_action("dgroups/deletetopic",false,$CONFIG->pluginspath . "dgroups/actions/forums/deletetopic.php");
	register_action("dgroups/addpost",false,$CONFIG->pluginspath . "dgroups/actions/forums/addpost.php");
	register_action("dgroups/edittopic",false,$CONFIG->pluginspath . "dgroups/actions/forums/edittopic.php");
	register_action("dgroups/deletepost",false,$CONFIG->pluginspath . "dgroups/actions/forums/deletepost.php");
	register_action("dgroups/featured",false,$CONFIG->pluginspath . "dgroups/actions/featured.php");
	register_action("dgroups/editpost",false,$CONFIG->pluginspath . "dgroups/actions/forums/editpost.php");
	
?>