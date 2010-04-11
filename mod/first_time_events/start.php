<?php

function check_first_login($login_event, $user_type, $user) {
  if($user && $user->last_login == 0) {
    trigger_elgg_event('firstlogin','user', $user);
  }
  }

register_elgg_event_handler('login','user','check_first_login');

?>