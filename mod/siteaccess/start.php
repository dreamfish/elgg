<?php

    function siteaccess_init()
    {
	global $CONFIG;

	$CONFIG->disable_registration = false;
	
	if (siteaccess_walledgarden_enabled()) {
	    if(!isloggedin()) {
		siteaccess_allowed_pages();
	    }
	}	

	register_plugin_hook('action', 'register', 'siteaccess_register_hook'); 
	register_plugin_hook('action', 'login', 'siteaccess_login_hook');
        register_plugin_hook('usersettings:save', 'user', 'siteaccess_user_settings_hook', 25);
	$period = get_plugin_setting('period','siteaccess');
                switch ($period)
                {
                        case 'hourly':
                        case 'daily' :
                        case 'weekly' :
			case 'monthly' :
                        break;
                        default: $period = 'weekly';
                }
	register_plugin_hook('cron', $period, 'siteaccess_cron_hook');

	if (isadminloggedin())
        {
	    register_page_handler('siteaccess', 'siteaccess_page_handler'); // Add siteaccess/index.php 
	    extend_view('profile/menu/adminlinks','siteaccess/menu/siteaccess_adminlinks'); // Add links to user profile
	    register_action("siteaccess/activate",false,$CONFIG->pluginspath . "siteaccess/actions/activate.php", true); // Enable validate action
            register_action("siteaccess/email/save",false,$CONFIG->pluginspath . "siteaccess/actions/siteaccess/email/save.php", true);
            register_action("siteaccess/email/delete",false,$CONFIG->pluginspath . "siteaccess/actions/siteaccess/email/delete.php", true);
	} 
	
	extend_view('css','siteaccess/css'); 
	register_action("siteaccess/confirm",true, $CONFIG->pluginspath . "siteaccess/actions/confirm.php");
	register_action("siteaccess/code",true, $CONFIG->pluginspath . "siteaccess/actions/code.php");
	register_elgg_event_handler('validate', 'user', 'siteaccess_validate_user');
	register_elgg_event_handler('create', 'user', 'siteaccess_create_user');
    }
	
    function siteaccess_allowed_pages() {
	global $CONFIG;

	$allowed = false;
	$p = parse_url($CONFIG->wwwroot);
	$base_url = $p['scheme'] . "://" . $p['host'];
	if ((isset($p['port'])) && ($p['port'])) $base_url .= ":" . $p['port'];
	$uri = preg_replace('#\?.*|\#.*#', '', $_SERVER['REQUEST_URI']);
	$url = $base_url . $uri;
	$accesslist = get_plugin_setting('accesslist','siteaccess');
	$accesslist = explode("\n", $accesslist);
	array_push($accesslist, 'action/login');
	array_push($accesslist, '_css/js.php');
	array_push($accesslist, '_css/css.css');
	array_push($accesslist, '');
	foreach($accesslist as $acl) {
	    $acl = trim($acl);
	    if(strcmp($url, $CONFIG->wwwroot . $acl) == 0) {
		$allowed = true;
		break;
	    }
	}

	$_SESSION['last_forward_from'] = '';
	if (!$allowed) {
	    $msg = elgg_echo('siteaccess:walledgarden:allow');
	    if (get_plugin_setting('wg_debug', 'siteaccess') == 'yes') {
		$uri = ltrim($uri, '/');
		$msg .= "\nRequest URI: $uri (Add this to your access list)\n";
	    }
	    register_error($msg); 
	    forward($CONFIG->url);
	}
    }
    
    function siteaccess_key_enabled()
    {
	$enabled = get_plugin_setting('usesiteaccesskey', 'siteaccess');	
	return ($enabled) == "yes" ? true : false;
    }
    
    function siteaccess_coppa_enabled()
    {
	$enabled = get_plugin_setting('usesiteaccesscoppa', 'siteaccess');
        if ($enabled == "yes")
	return ($enabled == "yes") ? true : false;
    }

    function siteaccess_email_enabled()
    {
	$enabled = get_plugin_setting('usesiteaccessemail', 'siteaccess');
        return ($enabled == "yes") ? true : false;
    }

    function siteaccess_invitecode_enabled()
    {
        $enabled = get_plugin_setting('invitecode', 'siteaccess');
        return ($enabled == "yes") ? true : false;
    }

    function siteaccess_walledgarden_enabled()
    {
        $enabled = get_plugin_setting('walledgarden', 'siteaccess');
        return ($enabled == "yes") ? true : false;
    }

    function siteaccess_river_enabled() {
	$enabled = get_plugin_setting('useriver', 'siteaccess');
	return ($enabled == "yes") ? true : false;
    }

    function siteaccess_cron_hook($hook, $entity_type, $returnvalue, $params) {
	global $CONFIG;

	$username = get_plugin_setting('notify', 'siteaccess');
	if ($username) {
	    $count = siteaccess_count_users('validated', '0');
	    if ($count > 0) {
		$user = get_user_by_username($username);
		if ($user) {
                    siteaccess_notify_user($user, 'notify_admin');
		}
	    }
	}
    }

    function siteaccess_generate_captcha($num) {
	global $CONFIG;
	$date = date("F j");
	$tmp = hexdec(md5($num . $date . $CONFIG->site->url . get_site_secret()));
	$code = substr($tmp, 4, 6);

        return $code;
    }

    function siteaccess_validate_captcha() {
	$code = get_input('code');
	$random = get_input('random');	
	
	$generated_code = siteaccess_generate_captcha($random);
	$valid = false;
	if ((trim($code) != "") && (strcmp($code, $generated_code) == 0))
            $valid = true;
	else
	    register_error(elgg_echo('siteaccess:code:invalid'));

	return $valid;
    }

    function siteaccess_auth_userpass($credentials = NULL) {
	if (is_array($credentials) && ($credentials['username']) && ($credentials['password'])) {
	    if ($user = get_user_by_username($credentials['username'])) {
		if ($user->password == generate_user_password($user, $credentials['password']))
		    return $user;
	    }
	}
	
	return false;
    }

    function siteaccess_user_settings_hook($hook, $entity_type, $returnvalue, $params) {
        global $CONFIG;

        $email = get_input('email');
        $user_id = get_input('guid');
        $user = get_entity($user_id);

        if ($user) {
            if (strcmp($email, $user->email) != 0) {
                $user->validated_email = false;
            }
        }
    }

    function siteaccess_login_hook($hook, $entity_type, $returnvalue, $params) {
	if (extension_loaded("gd")) {
	    $username = get_input('username');
	    $password = get_input('password');
	    $valid = false;
	    if (!empty($username) && !empty($password)) {
		if ($user = siteaccess_auth_userpass(array('username' => $username, 'password' => $password))) {
		    $valid = true;
		} else {
		    $_SESSION['login_error_count']++;
		}

		if ($_SESSION['login_error_count'] >  3) 
		    if ($valid = siteaccess_validate_captcha() && $user)
			reset_login_failure_count($user->guid);
	    }

	    if (!$valid)
		register_error(elgg_echo('loginerror'));
	} else {
	    $valid = true;
	} 

	return $valid;
    }

    function siteaccess_register_hook($hook, $entity_type, $returnvalue, $params) {
	$error = false;
	if (siteaccess_invitecode_enabled()){
	    $friend_guid = get_input('friend_guid');
	    $invitecode = get_input('invitecode');
	    if($friend_guid) {
		if ($friend_user = get_user($friend_guid)) {
		    if (!$invitecode == generate_invite_code($friend_user->username)) {
			$error = true;
		    }
		} else {
		    $error = true;
		}
	    } else {
		$error = true;
	    }
	}
	if ($error) 
	    register_error(elgg_echo('siteaccess:invitecode:invalid'));

	if (siteaccess_key_enabled()) {
	    $sitekey = get_plugin_setting('siteaccesskey', 'siteaccess');
	    $inputkey = get_input('siteaccesskey');
	    if ((trim($inputkey) == "") || (strcmp($inputkey, $sitekey) != 0)) {
		register_error(elgg_echo('siteaccess:key:invalid'));
		$error = true;
	    }
	}

	if (siteaccess_coppa_enabled()) {
	    $coppa = get_input('coppa');
	    if (!$coppa) {
		register_error(elgg_echo('siteaccess:coppa:fail'));
		$error = true;
	    }
	}

	if (extension_loaded("gd")) {
	    if (!siteaccess_validate_captcha()) {
		$error = true;
	    }
	}
	
	if ($error) {
	    siteaccess_register_fail();
	}
    }

    function siteaccess_register_fail() {
        $username = get_input('username');
        $email = get_input('email');
        $name = get_input('name');
        $friend_guid = get_input('friend_guid');
	$invitecode = get_input('invitecode');

        $qs = explode('?',$_SERVER['HTTP_REFERER']);
        $qs = $qs[0];
        $qs .= "?u=" . urlencode($username) . "&e=" . urlencode($email) . "&n=" . urlencode($name) . "&friend_guid=" . $friend_guid . "&invitecode=" . $invitecode;
        forward($qs);
    }

    function siteaccess_generate_code($user_guid, $email_address) {
        global $CONFIG;
        $date = date("W");
        $code = md5($user_guid . $email_address . $date . $CONFIG->site->url . get_site_secret());
	
	return $code;
    }

    function siteaccess_validate_user($event, $object_type, $object) {
	if (($object) && ($object instanceof ElggUser)) {
	    if (get_plugin_setting('autoactivate', 'siteaccess') == 'yes') {
                set_user_validation_status($object->guid, true, 'auto');
            }
	
	    $email_validated = $object->validated_email;
	    if (!$email_validated) {
		siteaccess_email_validation($object->guid);
	    } else {
		register_error(elgg_echo('siteaccess:authorize'));
		return false;
	    }
        }	
    }
    
    function siteaccess_create_user($event, $object_type, $object) {
	if (($object) && ($object instanceof ElggUser)) {
            create_metadata($object->guid, 'validated_email', false,'', 0, ACCESS_PUBLIC);
	    $friend_guid = get_input('friend_guid');
	    if ($friend = get_user($friend_guid)) {
		create_metadata($object->guid, 'invited_by_guid', $friend->guid,'', 0, ACCESS_PUBLIC);
	    }
            siteaccess_add_to_river($object, 'join');
	}
    }

    function siteaccess_email_validation($user_guid) {
	global $CONFIG;

        $user = get_entity($user_guid);

        if (($user) && ($user instanceof ElggUser))
        {
            // Send validation email
            $result = siteaccess_notify_user($user, 'confirm');
            if ($result)
		system_message(elgg_echo('siteaccess:confirm:email'));

            return $result;
        }

        return false;
    }

    function siteaccess_validate_email($user_guid, $code){
	$user = get_entity($user_guid);
	$valid = ($code == siteaccess_generate_code($user_guid, $user->email));
	if ($valid){
	    create_metadata($user_guid, 'validated_email', true,'', 0, ACCESS_PUBLIC);
	    if (siteaccess_email_enabled()) { 
		set_user_validation_status($user_guid, true, 'email');
                siteaccess_add_to_river($user, 'activate');
	    }
	}

	return $valid;
    }

    function siteaccess_pagesetup()
    {
	global $CONFIG;

	if(get_context() == 'admin' && isadminloggedin()) {
	    add_submenu_item(elgg_echo('siteaccess:admin:menu'), $CONFIG->wwwroot . 'pg/siteaccess/activate');
	}
    }

    function siteaccess_page_handler($page)
    {
	global $CONFIG;
	if (isset($page[0])) {
            set_input('show', $page[0]);    
            if (isset($page[1])) {
                set_input('friend_username', $page[1]); 
            }
	}
	include($CONFIG->pluginspath . 'siteaccess/index.php');
    }

    function siteaccess_count_users($meta_name, $meta_value) {
        if(isset($meta_name) && isset($meta_value)) {
            $count = get_entities_from_metadata($meta_name, $meta_value, 'user', '', 0, 0, 0, '', 0, true);
        }
        return $count;
    }

    function siteaccess_users($meta_name, $meta_value, $limit = 10, $offset = 0)
    {
        if(isset($meta_name) && isset($meta_value)) {
	    $entities = get_entities_from_metadata($meta_name, $meta_value, 'user', '', 0, $limit, $offset, '', 0);
        }
	return $entities;
    }
    
    function siteaccess_parser($user, $str) {
        global $CONFIG;
        if (($user) && ($user instanceof ElggUser)) {
            $confirm_url = $CONFIG->wwwroot . "action/siteaccess/confirm?u=$user->guid&c=" . siteaccess_generate_code($user->guid, $user->email);
            $admin_url = $CONFIG->wwwroot . 'pg/siteaccess/activate';
            $patterns = array('/%site_name%/', '/%site_url%/', '/%username%/', '/%name%/', '/%confirm_url%/', '/%admin_url%/');
            $replace = array($CONFIG->site->name, $CONFIG->site->url, $user->username, $user->name, $confirm_url, $admin_url);

            return preg_replace($patterns, $replace, $str);
        }

        return false;
    }

    function siteaccess_add_to_river($user, $type) {
        if (siteaccess_river_enabled()) {
            switch ($type) {
                case 'join':
                    add_to_river('river/siteaccess/join','join', $user->guid, $user->guid);
                    break;
                case 'activate':
                    add_to_river('river/siteaccess/activate','activate', $user->guid, $user->guid);
                    break;
                case 'admin':
                    add_to_river('river/siteaccess/admin','admin', $user->guid, $user->guid);
                    break;
            }
        }
    }

    function siteaccess_notify_user($user, $type) {
        global $CONFIG;
        if (($user) && ($user instanceof ElggUser)) {
            if ($email = siteaccess_get_email($type)) {
                $subject = siteaccess_parser($user, $email->title);
                $content = siteaccess_parser($user, $email->description);
                $result = notify_user(
                    $user->guid,
                    $CONFIG->site->guid,
                    $subject,
                    $content, NULL, 'email');
                return $result;
            }
        }

        return false;
    }

    function siteaccess_new_email($subject, $content) {
        $subject = sanitise_string($subject);
        //$content = sanitise_string($content);

        if ($subject && $content) {
            $email = new ElggObject();
            $email->subtype = 'siteaccess_email';
            $email->owner_guid = $CONFIG->site->guid;
            $email->access_id = ACCESS_PUBLIC;
            $email->title = $subject;
            $email->description = $content;

            return $email;
        }

        return false;
    }

    function siteaccess_get_email($type) {
        $update = false;
        switch ($type) {
            case 'admin_activated':
                $setting = 'admin_activated_email';
                $email_guid = get_plugin_setting($setting, 'siteaccess');
                $subject = elgg_echo('siteaccess:email:adminactivated:subject');
                $content = elgg_echo('siteaccess:email:adminactivated:content');
                break;
            case 'confirm':
                $setting = 'confirm_email';
                $email_guid = get_plugin_setting($setting, 'siteaccess');
                $subject = elgg_echo('siteaccess:email:confirm:subject');
                $content = elgg_echo('siteaccess:email:confirm:content');
                break;
            case 'validated':
                $setting = 'validated_email';
                $email_guid = get_plugin_setting($setting, 'siteaccess');
                $subject = elgg_echo('siteaccess:email:validated:subject');
                $content = elgg_echo('siteaccess:email:validated:content');
                break;
            case 'notify_admin':
                $setting = 'notify_admin_email';
                $email_guid = get_plugin_setting($setting, 'siteaccess');
                $subject = elgg_echo('siteaccess:email:notifyadmin:subject');
                $content = elgg_echo('siteaccess:email:notifyadmin:content');
                break;
        }

        if ($email_guid) {
            $email = get_entity($email_guid);
            if (!$email) {
                $update = true;
                $email = siteaccess_new_email($subject, $content); 
            }
        } else if ($setting) { // if setting is set then a valid optino was selected create email
            $update = true;
            $email = siteaccess_new_email($subject, $content); 
        }

        if ($update && $email && isadminloggedin()) {
            $email->save();
            set_plugin_setting($setting, $email->guid, 'siteaccess');
        }

        if ($email) {
            return $email;
        }

        return false;
    }

    register_elgg_event_handler('init','system','siteaccess_init');
    register_elgg_event_handler('pagesetup', 'system', 'siteaccess_pagesetup');
?>
