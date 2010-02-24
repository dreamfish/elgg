div#pfc_container {
  border: 1px solid #555;
  color: #000;
  padding: 10px;
  min-height: 20px;
  background-color: #FFF;
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/background.gif'); ?>");
  background-position: right;
/*  background-repeat: repeat-xy;*/
  font: 12px Trebuchet MS, Sans-Serif; /* without this rule, the tabs are not correctly display on FF */
  width: 640px;
}

#pfc_minmax {
  cursor: pointer;
}
/*bg of smilies and buttons*/
div#pfc_content_expandable {
  margin-top: 0.2em;
  
}

/*bg of the chat-messages*/
div#pfc_channels_content {
  z-index: 20;
  position: relative;

  border-right: 2px solid #555;
  border-left: 1px solid #555;
  border-bottom: 2px solid #555;
  background-color: #FFF;
  margin-top: 5px;

  background-image: url("<?php echo $c->getFileUrlFromTheme('images/channels_content_bg.png'); ?>"); 
  width: 640px;  
}
div.pfc_content {

}

/* channels tab-panes */
ul#pfc_channels_list {
  list-style-type: none;
  display: block;
  z-index: 50;
  border-bottom: 1px solid #555;
  margin-bottom: -5px;
}
ul#pfc_channels_list li {
  display: inline;
  margin-left: 5px;
}
/*tab-channel OFF*/
ul#pfc_channels_list li div {
  display: inline;
  padding: 0 4px 0 4px;
  border: 1px solid #555;
  background-color: #DDD;
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/tab_off.png'); ?>");
  font-size: 11px;
  font-weight: bold;
  
/*these 2 lines below is to make the tabs looks the same in IE and FF */  
  padding-bottom: 6px;
  line-height: 26px;
}
/*tab-channel ON*/
ul#pfc_channels_list li.selected div {
  background-color: #FFF;
  color: #000;
  font-weight: bold;
  font-size: 11px;
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/tab_on.png'); ?>");

/*these 2 lines below is to make the tabs looks the same in IE and FF */
  PADDING-BOTTOM: 6px;
  line-height: 26px;
}
ul#pfc_channels_list li > div:hover {
  background-color: #FFF;
}
/*tab-channel text*/
ul#pfc_channels_list li a {
  color: #333	;
  text-decoration: none;  
}
ul#pfc_channels_list li a.pfc_tabtitle {
  cursor: pointer;
}
ul#pfc_channels_list li a.pfc_tabtitle img {
  padding-right: 4px;
}
ul#pfc_channels_list li a.pfc_tabclose {
  margin-left: 4px;
  cursor: pointer;
}

/*where should the newmsg- and oldmsg pictures be placed? decide it here*/
div.pfc_chat {
  z-index: 100;
  position: absolute;
  top: 0px;
  left: 3px;
	right: 0px;
	bottom: 3px;
  width: 467px;
/* WARNING: do not fix height in % because it will display blank screens on IE6 */
/*  height: 100%;*/
  overflow: auto;
}

/*usernames-onlinelist*/
div.pfc_online {
  position: absolute;
  right: 0px;
  top: 0px;
  padding: 0px;
  overflow: auto;
  width: 171px;
  border-bottom: 1px solid #555;
/* WARNING: do not fix height in % because it will display blank screens on IE6 */
/*  height: 100%;*/
  color: #000; /* colors can be overriden by js nickname colorization */
  background-color: #FFF;
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/pfc_online.png'); ?>");
  background-position: left;
  background-repeat: repeat-y;
  /* borders are drawn by the javascript routines */
}
div.pfc_online ul {
  list-style-type: none;
  margin: 0px;
  padding: 0px;
  margin-left: 8px;
  margin-right: 8px;
}
div.pfc_online li {
  font-weight: bold;
  font-size: 12px;
  cursor: pointer;
  /* bottom borders are drawn by the javascript routines */
}

h2#pfc_title {
  font-size: 110%;
}

img#pfc_minmax {
  float: right;
}

.pfc_invisible {
  display: none;
}

.pfc_oddmsg {
  background-color: #fff;
  color: #000;
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/pfc_message1.png'); ?>");
}
.pfc_evenmsg {
  background-color: #ccc;
  color: #000;
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/pfc_message2.png'); ?>");
}

div.pfc_message {
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/newmsg.png'); ?>");
  background-position: right;
  background-repeat: no-repeat; 
}

div.pfc_oldmsg {
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/oldmsg.png'); ?>");
  background-position: right;
  background-repeat: no-repeat; 
}

span.pfc_heure, span.pfc_date {
  color: #333;
  font-size: 90%;
}

span.pfc_nick {
  color: #fbac17;
  font-weight: bold;
  cursor:pointer;
}

div#pfc_input_container {
  margin-top: 5px;
  font-size: 12px;
}
p#pfc_handle { display: none; }

div#pfc_input_container td.pfc_td2 {
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/pfc_words.png'); ?>");
  background-repeat: no-repeat;
  padding-left: 5px;
}

input#pfc_words {
  border: 0px;
  background-color: #FAFAFA;
  width: 520px;
  font-size: 12px;
  height: 20px;
  vertical-align: bottom;
  font: 12px Trebuchet MS;
}

input#pfc_send {
  display: block;
  margin-left: 5px;
  padding-top: 2px;
  width: 100px;
  border: 0px;
  background-color: #ccc;
  font: 12px Trebuchet MS;
  color: #333;
  height: 24px;
  cursor: pointer;
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/pfc_send.png'); ?>");
  cursor: pointer;
}



div#pfc_cmd_container {
  position: relative;
  margin-top: 5px;
  margin-bottom: 5px;
  width: 100%;
}
div#pfc_cmd_container * {
  margin-right: 2px;
}



a#pfc_logo {
  position: absolute;
  right: 0px;
  top: 0px;
}

div.pfc_btn {
  display: inline;  
  cursor: pointer;
}

div#pfc_bbcode_container * {
  margin-right: 2px;
}

div#pfc_errors {
  display: none;
  padding: 5px;
  border: 1px solid #555;
  color: #EC4B0F;
  background-color: #FFBB77;
  font-style: italic;
  font-family: monospace;
  font-size: 90%;
}

/* commands */
.pfc_cmd_msg {
  color: black;
}
.pfc_cmd_me {
  font-style: italic;
  color: black;
}
/*notice messages, login,logout,timed out etc..*/
.pfc_cmd_notice {
  font-style: italic;
  color: #333;
}

/* commands info */
.pfc_info {
  color: #fefefe;

  /* to fix IE6 display bug */
  /* http://sourceforge.net/tracker/index.php?func=detail&aid=1545403&group_id=158880&atid=809601 */
  font-family: sans-serif; /* do NOT setup monospace font or it will not work in IE6 */
  font-style: italic;
  background-color: #EEE;
  font-size: 80%;
}


div#pfc_colorlist {
  display: none;
}
img.pfc_color {
  padding: 1px;
  cursor: pointer;
}

.pfc_nickmarker {
  white-space: pre;

}

div#pfc_smileys {
  display: none; /* will be shown by javascript routines */
  background-color: #FFF;
  border: 1px solid #555;
  padding: 4px;
  margin-top: 4px;
}
div#pfc_smileys * {
  margin-right: 2px;
}

div#pfc_smileys img {
 cursor: pointer;
}



div.pfc_nickwhois * { padding: 0; margin: 0; }
div.pfc_nickwhois a img { border: none; }
div.pfc_nickwhois {
  border: 1px solid #444;
  background-color: #FFF;
  font-size: 75%;
}
div.pfc_nickwhois ul {
  list-style-type: none;
  background-color: #EEE;
  border-bottom: 1px solid #444;
}
div.pfc_nickwhois li {
  display: inline;
  margin-right: 4px;
  padding: 2px;
}
td.pfc_nickwhois_c1 {
  font-weight: bold;
}
li.pfc_nickwhois_pv {
  padding-left: 2px;
  border-left: 1px solid #444;
}
li.pfc_nickwhois_pv a {
	text-decoration: none;
}

ul.pfc_nicklist span.pfc_nickmarker {
}

img.pfc_nickbutton {
  cursor: pointer;
}