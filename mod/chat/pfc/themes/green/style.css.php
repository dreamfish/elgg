div#pfc_container {
  border: 1px solid #555;
  color: #338822;
  background-color: #d9edd8;
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/shade.gif'); ?>");
  background-position: right;
  background-repeat: repeat-y;
}

div#pfc_channels_content {
  border-right: 1px solid #555;
  border-left: 1px solid #555;
  border-bottom: 1px solid #555;
  background-color: #e0edde;
}

/* channels tabpanes */
ul#pfc_channels_list {
  border-bottom: 1px solid #555;
}
ul#pfc_channels_list li div {
  border-top: 1px solid #555;
  border-right: 1px solid #555;
  border-left: 1px solid #555;
  border-bottom: 1px solid #555;
  background-color: #7dc073;
}
ul#pfc_channels_list li.selected div {
  background-color: #e0edde;
  border-bottom: 1px solid #e0edde;
  color: #000;
}
ul#pfc_channels_list li div:hover {
  background-color: #e0edde;
}
ul#pfc_channels_list li a {
  color: #000;
}

div.pfc_smileys {
  border: 1px solid #000;
  background-color: #EEE;
}

h2#pfc_title {
  font-size: 110%;
}

div.pfc_oldmsg {
}

span.pfc_heure {
  color: #bebebe;
}
span.pfc_date {
  color: #bebebe;
}

span.pfc_nick {
  color: #fbac17;
}

/* commands */
.pfc_cmd_msg {
  color: black;
}
.pfc_cmd_me {
  font-style: italic;
  color: black;
}
.pfc_cmd_notice {
  font-style: italic;
  color: #888;
}
pre.pfc_cmd_rehash
{
  color: #888;
  font-style: italic;
}

pre.pfc_cmd_help
{
  color: #888;
  font-style: italic;
}