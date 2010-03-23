<?php

define('ELGG_VF_ELGG_AUTH_COOKIE_NAME', 'Elgg');
define('ELGG_SITE_ID', 1);

require_once(dirname(dirname(dirname(__FILE__))).'/engine/settings.php');

define('ELGG_DB_HOST', $CONFIG->dbhost);
define('ELGG_DB_NAME', $CONFIG->dbname);
define('ELGG_DB_USER', $CONFIG->dbuser);
define('ELGG_DB_PASSWORD', $CONFIG->dbpass);
define('ELGG_DB_PREFIX', $CONFIG->dbprefix);

// get the Elgg root URL

$query = "SELECT url FROM ".ELGG_DB_PREFIX."sites_entity WHERE guid = ".ELGG_SITE_ID;

$cnx = mysql_connect(ELGG_DB_HOST, ELGG_DB_USER, ELGG_DB_PASSWORD);
if(!$cnx) {
	error_log('Could not connect to database: ' . mysql_error());
} else {
	if(!mysql_select_db(ELGG_DB_NAME, $cnx)) {			
		error_log('Could not select database: ' . mysql_error());
	} else {
		if(!$rs = mysql_query($query, $cnx)) {
			error_log('Could not execute query: ' . mysql_error());
		} else {
			$row = mysql_fetch_assoc($rs);
			define('ELGG_URL', $row['url']);
		}
	}
}

?>