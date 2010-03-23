<?php
function vanillaforum_sync_user_data($username,$name,$email) {
	$username = sanitise_string($username);
	$firstname = sanitise_string($name);
	$email = sanitise_string($email);

	$vquery = "UPDATE LUM_User SET FirstName = '$firstname', Email = '$email' WHERE Name = '$username'";
	vanillaforum_vanilla_query($vquery,false);
	return true;
}

function vanillaforum_sync_settings() {
	$user = page_owner_entity();
	if (!$user) {
		$user = $_SESSION['user'];
	}
	$username = $user->username;
	$name = get_input('name');
	$email = get_input('email');
	vanillaforum_sync_user_data($username,$name,$email);
}

function vanillaforum_forum_create($group) {

	// Create a new category in Vanilla
	$group_guid = $group->getGUID();
	$forum_desc = sanitise_string(sprintf(elgg_echo('vanillaforum:forum_description'), $group->name));
	$forum_name = sanitise_string(sprintf(elgg_echo('vanillaforum:forum_name'), $group->name));
	$query = "SELECT CategoryID from LUM_Category where CategoryID = ".$group_guid;
	$result = vanillaforum_vanilla_query($query);
	if (!$result) {
		$query = "INSERT INTO LUM_Category (CategoryID,Name,Description) VALUES (" . $group_guid . ",'" . $forum_name . "','" . $forum_desc . "')";
		vanillaforum_vanilla_query($query,false);
		$query = "INSERT INTO LUM_CategoryRoleBlock (CategoryID,RoleID,Blocked) VALUES ($group_guid,1,1)";
		vanillaforum_vanilla_query($query,false);
		$query = "INSERT INTO LUM_CategoryRoleBlock (CategoryID,RoleID,Blocked) VALUES ($group_guid,2,1)";
		vanillaforum_vanilla_query($query,false);
	}
}

function vanillaforum_forum_update($event,$object_type,$group) {
	$group_guid = $group->getGUID();
	// respond to name changes
	$forum_desc = sanitise_string(sprintf(elgg_echo('vanillaforum:forum_description'), $group->name));
	$forum_name = sanitise_string(sprintf(elgg_echo('vanillaforum:forum_name'), $group->name));
	$query = "UPDATE LUM_Category SET Name = '" . $forum_name . "', Description='" . $forum_desc . "'";
	$query .= " WHERE CategoryID=".$group_guid;
	vanillaforum_vanilla_query($query,false);
	 
	return true;
}

function vanillaforum_forum_delete($event,$object_type,$group) {
	$group_guid = $group->getGUID();
	// Drop Vanilla category
	// TODO: shouldn't this delete all discussion posts as well?
	$query = "SELECT CategoryID FROM LUM_Category WHERE CategoryID=".$group_guid;
	$result = vanillaforum_vanilla_query($query);
	if ($result) {
		$query = "UPDATE LUM_Discussion SET CategoryID = 1 WHERE CategoryID = " . $group_guid;
		vanillaforum_vanilla_query($query,false);
		vanillaforum_vanilla_query("DELETE FROM LUM_Category WHERE CategoryID=" . $group_guid,false);
		vanillaforum_vanilla_query("DELETE FROM LUM_CategoryRoleBlock WHERE CategoryID=" . $group_guid,false);
	}
	return true;
}

function vanillaforum_vanilla_query($query,$select=true) {

	global $CONFIG;

	static $cnx, $vanilla_db_host, $vanilla_db_user, $vanilla_db_password, $vanilla_db_name;

	if (!$cnx) {
		$Configuration = array();
		$DatabaseTables = array();
		include(dirname(dirname(__FILE__)).'/vanilla/conf/database.php');
		
		$vanilla_db_host = $Configuration['DATABASE_HOST'];
		$vanilla_db_user = $Configuration['DATABASE_USER'];
		$vanilla_db_password = $Configuration['DATABASE_PASSWORD'];
		$vanilla_db_name = $Configuration['DATABASE_NAME'];
			
		$cnx = mysql_connect($vanilla_db_host, $vanilla_db_user, $vanilla_db_password);
	}
		

	if(!$cnx)
	{
		error_log('Could not connect to database: ' . mysql_error());
	}

	if(!mysql_select_db($vanilla_db_name, $cnx))
	{
			
		error_log('Could not select database: ' . mysql_error());
	}

	if(!$rs = mysql_query($query, $cnx))
	{
		error_log('Could not execute query: ' . mysql_error());
	}
	 
	if ($select) {
		 
		$rows = array();

		while ($row = mysql_fetch_assoc($rs))
		{
			$rows[] = $row;
		}
		// switch back to Elgg database
		$elgg_cnx = mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass);
		mysql_select_db($CONFIG->dbname, $elgg_cnx);

		return $rows;
	} else {
		return true;
	}
}

function vanillaforum_add_to_river($type) {
	$vf_username = get_input('username','');
	$user = get_user_by_username($vf_username);
	if ($user) {
		$uid = $user->getGUID();
		// need to create a notification object to get the river system to work properly
		$obj = new ElggObject();
		$obj->subtype = 'vanillaforum_notify';
		$obj->owner_guid = $uid;
		$obj->container_guid = $uid;
		$obj->access_id = ACCESS_PUBLIC;
		$obj->title = get_input('name','');
		$obj->description = get_input('category','');
		$obj->did = get_input('did',0);
		$obj->cid = get_input('cid',0);
		$obj->post_type = $type;
		
		$obj->save();
		
		$nid = $obj->getGUID();

		add_to_river("river/object/$type/create",'create',$uid,$nid);
	}
}
?>