
div#pfc_container {
  color: #2A4064;
  background-color: #BEC5D0;
  background-image: url("<?php echo $c->getFileUrlFromTheme('images/shade.gif'); ?>");
  background-repeat: repeat-y;
}

div.pfc_chat {
  background-color:#CED4DF;
}

div.pfc_message {
  background-color:#CED4DF;
}

div.pfc_oldmsg {
  /*background-image: none;
  background-color:#DCDEE4;*/
}

span.pfc_nick {
  color:#2A4064;
}

div#pfc_errors {
  display: none;
  margin-top: 5px;
  padding: 2px;
  border: #555 solid 1px;
  color: #EC4A1F;
  background-color: #BEC5D0;
  font-style: italic;
  font-weight: bold;
}

ul#pfc_channels_list li div {
  background-color: #bec5d0;
  border-bottom: 1px solid #bec5d0;
}
ul#pfc_channels_list li.selected div {
  background-color: #CED4DF;
  border-bottom: 1px solid #CED4DF;
  color: #000;
  font-weight: bold;
}
ul#pfc_channels_list li div:hover {
  background-color: #CED4DF;
  border-bottom: 1px solid #CED4DF;
}
ul#pfc_channels_list li.selected div:hover {
  background-color: #CED4DF;
}
