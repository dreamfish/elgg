<?php $pfc_conf = array (
  'serverid' => 'phpfreechat',
  'language' => '',
  'output_encoding' => 'UTF-8',
  'max_nick_len' => 15,
  'frozen_nick' => false,
  'nickmeta_private' => 
  array (
    0 => 'ip',
  ),
  'nickmeta_key_to_hide' => 
  array (
  ),
  'firstisadmin' => false,
  'title' => 'Chat',
  'max_channels' => 10,
  'max_privmsg' => 5,
  'refresh_delay' => 2000,
  'refresh_delay_steps' => 
  array (
    0 => 2000,
    1 => 20000,
    2 => 3000,
    3 => 30000,
    4 => 5000,
    5 => 60000,
    6 => 8000,
    7 => 300000,
    8 => 15000,
    9 => 600000,
    10 => 30000,
  ),
  'timeout' => 35000,
  'lockurl' => 'http://www.phpfreechat.net',
  'skip_proxies' => 
  array (
  ),
  'post_proxies' => 
  array (
  ),
  'pre_proxies' => 
  array (
  ),
  'proxies_cfg' => 
  array (
    'auth' => 
    array (
    ),
    'noflood' => 
    array (
      'charlimit' => 450,
      'msglimit' => 10,
      'delay' => 5,
    ),
    'censor' => 
    array (
      'words' => 
      array (
        0 => 'fuck',
        1 => 'sex',
        2 => 'bitch',
      ),
      'replaceby' => '*',
      'regex' => false,
    ),
    'log' => 
    array (
      'path' => '',
    ),
  ),
  'proxies_path' => '/home/symfony/elgg/mod/chat/pfc/src/proxies',
  'proxies_path_default' => '/home/symfony/elgg/mod/chat/pfc/src/proxies',
  'cmd_path' => '/home/symfony/elgg/mod/chat/pfc/src/commands',
  'cmd_path_default' => '/home/symfony/elgg/mod/chat/pfc/src/commands',
  'max_text_len' => 400,
  'max_msg' => 20,
  'max_displayed_lines' => 150,
  'quit_on_closedwindow' => true,
  'focus_on_connect' => true,
  'connect_at_startup' => true,
  'start_minimized' => false,
  'height' => '440px',
  'shownotice' => 3,
  'nickmarker' => true,
  'clock' => true,
  'startwithsound' => true,
  'openlinknewwindow' => true,
  'notify_window' => true,
  'short_url' => true,
  'short_url_width' => 40,
  'display_ping' => true,
  'display_pfc_logo' => true,
  'displaytabimage' => true,
  'displaytabclosebutton' => true,
  'showwhosonline' => true,
  'showsmileys' => true,
  'btn_sh_whosonline' => true,
  'btn_sh_smileys' => true,
  'bbcode_colorlist' => 
  array (
    0 => '#FFFFFF',
    1 => '#000000',
    2 => '#000055',
    3 => '#008000',
    4 => '#FF0000',
    5 => '#800000',
    6 => '#800080',
    7 => '#FF5500',
    8 => '#FFFF00',
    9 => '#00FF00',
    10 => '#008080',
    11 => '#00FFFF',
    12 => '#0000FF',
    13 => '#FF00FF',
    14 => '#7F7F7F',
    15 => '#D2D2D2',
  ),
  'nickname_colorlist' => 
  array (
    0 => '#CCCCCC',
    1 => '#000000',
    2 => '#3636B2',
    3 => '#2A8C2A',
    4 => '#C33B3B',
    5 => '#C73232',
    6 => '#80267F',
    7 => '#66361F',
    8 => '#D9A641',
    9 => '#3DCC3D',
    10 => '#1A5555',
    11 => '#2F8C74',
    12 => '#4545E6',
    13 => '#B037B0',
    14 => '#4C4C4C',
    15 => '#959595',
  ),
  'theme' => 'green',
  'theme_path' => '/home/symfony/elgg/mod/chat/pfc/src/../themes',
  'theme_url' => 'http://192.168.2.9/elgg/mod/chat/pfc/data/public/themes',
  'theme_default_path' => '/home/symfony/elgg/mod/chat/pfc/src/../themes',
  'theme_default_url' => 'http://192.168.2.9/elgg/mod/chat/pfc/data/public/themes',
  'container_type' => 'File',
  'server_script_path' => '/home/symfony/elgg/mod/chat/pfc/index.php',
  'server_script_url' => 'http://192.168.2.9/elgg/mod/chat/pfc/index.php',
  'client_script_path' => '/home/symfony/elgg/mod/chat/pfc/index.php',
  'data_private_path' => '/home/symfony/elgg/mod/chat/pfc/src/../data/private',
  'data_public_path' => '/home/symfony/elgg/mod/chat/pfc/src/../data/public',
  'data_public_url' => 'http://192.168.2.9/elgg/mod/chat/pfc/data/public',
  'elgg_relative_urls' => true,
  'prototypejs_url' => 'http://192.168.2.9/elgg/mod/chat/pfc/data/public/js/prototype.js',
  'debug' => false,
  'get_ip_from_xforwardedfor' => false,
  'dyn_params' => 
  array (
  ),
  'proxies' => 
  array (
    0 => 'lock',
    1 => 'checktimeout',
    2 => 'checknickchange',
    3 => 'auth',
    4 => 'noflood',
    5 => 'censor',
    6 => 'log',
  ),
  'smileys' => 
  array (
    'smileys/emoticon_smile.png' => 
    array (
      0 => ':-)',
      1 => '^_^',
      2 => ':)',
    ),
    'smileys/emoticon_evilgrin.png' => 
    array (
      0 => '&gt;(',
    ),
    'smileys/emoticon_surprised.png' => 
    array (
      0 => ':S',
      1 => ':s',
      2 => ':-S',
      3 => ':-s',
      4 => ':-/',
    ),
    'smileys/emoticon_grin.png' => 
    array (
      0 => ':-D',
      1 => ':D',
    ),
    'smileys/emoticon_unhappy.png' => 
    array (
      0 => ':\\\'(',
      1 => ':-(',
      2 => ':o(',
      3 => ':-&lt;',
      4 => ':(',
    ),
    'smileys/emoticon_happy.png' => 
    array (
      0 => ':lol:',
    ),
    'smileys/emoticon_waii.png' => 
    array (
      0 => ':{}',
      1 => ':-{}',
      2 => ':razz:',
      3 => ':}',
      4 => ':-}',
    ),
    'smileys/emoticon_wink.png' => 
    array (
      0 => ';-)',
      1 => ';o)',
      2 => ';)',
    ),
    'smileys/emoticon_tongue.png' => 
    array (
      0 => ':P',
      1 => ':-P',
      2 => ':-p',
      3 => ':p',
    ),
    'smileys/weather_rain.png' => 
    array (
      0 => '///',
      1 => '\\\\\\\\\\\\',
      2 => '|||',
      3 => ':rain:',
      4 => ':drizzle:',
    ),
    'smileys/weather_snow.png' => 
    array (
      0 => ':***:',
    ),
    'smileys/weather_sun.png' => 
    array (
      0 => '&gt;O&lt;',
    ),
    'smileys/weather_clouds.png' => 
    array (
      0 => ':\\&quot;\\&quot;\\&quot;:',
      1 => ':cloud:',
      2 => ':clouds:',
    ),
    'smileys/weather_cloudy.png' => 
    array (
      0 => ':\\&quot;O\\&quot;:',
      1 => ':cloudly:',
    ),
    'smileys/weather_lightning.png' => 
    array (
      0 => ':$:',
    ),
    'smileys/arrow_right.png' => 
    array (
      0 => '=&gt;',
      1 => '-&gt;',
      2 => '--&gt;',
      3 => '==&gt;',
      4 => '&gt;&gt;&gt;',
    ),
    'smileys/arrow_left.png' => 
    array (
      0 => '&lt;=',
      1 => '&lt;-',
      2 => '&lt;--',
      3 => '&lt;==',
      4 => '&lt;&lt;&lt;',
    ),
    'smileys/exclamation.png' => 
    array (
      0 => ':!:',
    ),
    'smileys/lightbulb.png' => 
    array (
      0 => '*)',
      1 => '0=',
    ),
  ),
  'errors' => 
  array (
  ),
  'is_init' => true,
  'version' => '1.2',
  'container_cfg_chat_dir' => '/home/symfony/elgg/mod/chat/pfc/src/../data/private/chat',
  'container_cfg_server_dir' => '/home/symfony/elgg/mod/chat/pfc/src/../data/private/chat/s_phpfreechat',
);
?>