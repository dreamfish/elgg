<?php

function perform_redirect(){
  global $CONFIG;
  
  $username = get_loggedin_user()->username;
  
  $custom = get_plugin_setting("custom_redirect","new_profile_redirector");
  
  if(!get_loggedin_user()->profile_updated && !empty($custom)){
    $custom = str_replace("[wwwroot]",$CONFIG->wwwroot,$custom);
    $custom = str_replace("[username]",$username,$custom);

    get_loggedin_user()->profile_updated=1;

    trigger_elgg_event('firstprofileupdate', 'user', $user);

    forward($custom);
  }
  
}

register_elgg_event_handler('profileupdate', 'user', 'perform_redirect');

?>
