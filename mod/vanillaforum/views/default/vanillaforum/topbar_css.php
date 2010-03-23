/* ***************************************
  HORIZONTAL ELGG TOPBAR
  (tweaked to play nice with vanillaforum)
*************************************** */
#elgg_topbar {
	background:#333333 url(<?php echo $vars['url']; ?>_graphics/toptoolbar_background.gif) repeat-x top left;
	color:#eeeeee;
	border-bottom:1px solid #000000;
	min-width:998px;
	position:relative;
	width:100%;
	height:24px;
	z-index: 9000; /* if you have multiple position:relative elements, then IE sets up separate Z layer contexts for each one, which ignore each other */
}
#elgg_topbar a {
	text-decoration: none;
	outline-style:none;
	outline-width:medium;
	font-size: 105% !important;
	font-family: "Lucida Grande", Verdana, sans-serif !important;
	line-height: 1.4em !important;
}

#elgg_topbar_container_left {
	float:left;
	height:24px;
	left:0px;
	top:0px;
	position:absolute;
	text-align:left;
	width:60%;
}
#elgg_topbar_container_right {
	float:right;
	height:24px;
	position:absolute;
	right:0px;
	top:0px;
	/* width:120px;*/
	text-align:right;
}
#elgg_topbar_container_right small {
	font-size: 90%;
}
#elgg_topbar_container_search {
	float:right;
	height:21px;
	/*width:280px;*/
	position:relative;
	right:120px;
	text-align:right;
	margin:3px 0 0 0;
}
#elgg_topbar_container_search form {
	margin:0;
	padding:0;
}
#elgg_topbar_container_left .toolbarimages {
	float:left;
	margin-right:20px;
}
#elgg_topbar_container_left .toolbarlinks {
	margin:0 0 10px 0;
	float:left;
}
#elgg_topbar_container_left .toolbarlinks2 {
	margin:3px 0 0 0;
	float:left;
}
#elgg_topbar_container_left a.loggedinuser {
	color:#eeeeee;
	font-weight:bold;
	margin:0 0 0 5px;
}
#elgg_topbar_container_left a.pagelinks {
	color:white;
	margin:0 15px 0 5px;
	display:block;
	padding:3px;
}
#elgg_topbar_container_left a.pagelinks:hover {
	background: #4690d6;
	text-decoration: none;
}
#elgg_topbar_container_left a.privatemessages {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/toolbar_messages_icon.gif) no-repeat left 2px;
	padding:0 0 4px 16px;
	margin:0 15px 0 5px;
	cursor:pointer;
	text-decoration: none;
}
#elgg_topbar_container_left a.privatemessages:hover {
	text-decoration: none;
	background:transparent url(<?php echo $vars['url']; ?>_graphics/toolbar_messages_icon.gif) no-repeat left -36px;
}
#elgg_topbar_container_left a.privatemessages_new {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/toolbar_messages_icon.gif) no-repeat left -17px;
	padding:0 0 0 18px;
	margin:0 15px 0 5px;
	color:white;
	cursor:pointer;
	text-decoration: none;
}
/* IE6 */
* html #elgg_topbar_container_left a.privatemessages_new { background-position: left -18px; } 
/* IE7 */
*+html #elgg_topbar_container_left a.privatemessages_new { background-position: left -18px; } 

#elgg_topbar_container_left a.privatemessages_new:hover {
	text-decoration: none;
}

#elgg_topbar_container_left a.usersettings {
	margin:0 0 0 20px;
	color:#999999;
	padding:3px;
}
#elgg_topbar_container_left a.usersettings:hover {
	color:#eeeeee;
	text-decoration: underline;
}
#elgg_topbar_container_left img {
	margin:0 0 0 5px;
	border:none;
}
#elgg_topbar_container_left .user_mini_avatar {
	border:1px solid #eeeeee;
	margin:0 0 0 20px;
}
#elgg_topbar_container_right {
	padding:3px 0 0 0;
}
#elgg_topbar_container_right a {
	color:#eeeeee;
	margin:0 5px 0 0;
	background:transparent url(<?php echo $vars['url']; ?>_graphics/elgg_toolbar_logout.gif) no-repeat top right;
	padding:0 21px 0 0;
	display:block;
	height:20px;
}
/* IE6 fix */
* html #elgg_topbar_container_right a { 
	width: 120px;
}
#elgg_topbar_container_right a:hover {
	background-position: right -21px;
	text-decoration: underline;
}
#elgg_topbar_panel {
	background:#333333;
	color:#eeeeee;
	height:200px;
	width:100%;
	padding:10px 20px 10px 20px;
	display:none;
	position:relative;
}

#elgg_topbar input {
	font: 12px/100% Arial, Helvetica, sans-serif !important;
	font-weight: bold !important;
	padding: 5px;
	border: 1px solid #cccccc;
	color:#666666;
	-webkit-border-radius: 5px; 
	-moz-border-radius: 5px;
}
#elgg_topbar input[type="submit"] {
	font: 12px/100% Arial, Helvetica, sans-serif !important;
	font-weight: bold !important;
	color: #ffffff;
	background:#4690d6;
	border: 1px solid #4690d6;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	width: auto;
	height: 25px;
	padding: 2px 6px 2px 6px;
	margin:10px 0 10px 0;
	cursor: pointer;
}

#searchform input.search_input {
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	background-color:#FFFFFF;
	border:1px solid #BBBBBB;
	color:#999999;
	margin:0pt;
	padding:2px;
	width:180px;
	height:12px;
}
#searchform input.search_submit_button {
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	color:#333333;
	background: #cccccc;
	border:none;
	font-size:12px;
	margin:0px;
	padding:2px;
	width:auto;
	height:18px;
	cursor:pointer;
}
#searchform input.search_submit_button:hover {
	color:#ffffff;
	background: #4690d6;
}


/* ***************************************
	TOP BAR - VERTICAL TOOLS MENU
*************************************** */
/* elgg toolbar menu setup */
ul.topbardropdownmenu, ul.topbardropdownmenu ul {
	margin:0;
	padding:0;
	display:inline;
	float:left;
	list-style-type: none;
	z-index: 9000;
	position: relative;
}
ul.topbardropdownmenu {
	margin:0pt 20px 0pt 5px;
}
ul.topbardropdownmenu li { 
	display: block;
	list-style: none;
	margin: 0;
	padding: 0;
	float: left;
	position: relative;
}
ul.topbardropdownmenu a {
	display:block;
}
ul.topbardropdownmenu ul {
	display: none;
	position: absolute;
	left: 0;
	margin: 0;
	padding: 0;
}
/* IE6 fix */
* html ul.topbardropdownmenu ul {
	line-height: 1.1em;
}
/* IE6/7 fix */
ul.topbardropdownmenu ul a {
	zoom: 1;
} 
ul.topbardropdownmenu ul li {
	float: none;
}   
/* elgg toolbar menu style */
ul.topbardropdownmenu ul {
	width: 150px;
	top: 24px;
	border-top:1px solid black;
}
ul.topbardropdownmenu *:hover {
	background-color: none;
}
ul.topbardropdownmenu a {
	padding:3px;
	text-decoration:none;
	color:white;
}
ul.topbardropdownmenu li.hover a {
	background-color: #4690d6;
	text-decoration: none;
}
ul.topbardropdownmenu ul li.drop a {
	font-weight: normal;
}
/* IE7 fixes */
*:first-child+html #elgg_topbar_container_left a.pagelinks {

}
*:first-child+html ul.topbardropdownmenu li.drop a.menuitemtools {
	padding-bottom:6px;
}
ul.topbardropdownmenu ul li a {
	background-color: #999999;/* menu off state color */
	font-weight: bold !important;
	padding-left:6px;
	padding-top:4px;
	padding-bottom:0;
	height:22px;
	border-bottom: 1px solid white;
}
ul.topbardropdownmenu ul a.hover {
	background-color: #333333;
}
ul.topbardropdownmenu ul a {
	opacity: 0.9;
	filter: alpha(opacity=90);
}





