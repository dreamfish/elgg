.siteaccess_entity {
    float:left;
}

.siteaccess_links {
    text-align:right;
    margin:0;
    padding:0px;
}

.siteaccess_links a {
    color:red;
    padding: 1px 5px;
    margin-right:20px;
}

.siteaccess_links a:hover {
    text-decoration:none;
    color:white !important;
    background:red !important;
}

.siteaccess .search_listing {
        border:2px solid #cccccc;
        margin:0 0 5px 0;
}
.siteaccess .search_listing:hover {
        background:#dedede;
}
.siteaccess .group_count {
        font-weight: bold;
        color: #666666;
        margin:0 0 5px 4px;
}
.siteaccess .search_listing_info {
        color:#666666;
}

.siteaccess .profile_status {
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        background:#bbdaf7;
        line-height:1.2em;
        padding:2px 4px;
}
.siteaccess .profile_status span {
        font-size:90%;
        color:#666666;
}
.siteaccess  p.owner_timestamp {
        padding-left:3px;
}
.siteaccess .pagination {
        border:2px solid #cccccc;
        margin:5px 0 5px 0;
}

.siteaccess_validated {
    color: #009933;
}

.siteaccess_notvalidated {
    color: #990000;
}

#siteaccess-code {
}

#siteaccess-code td {
    vertical-align: middle;
}

#siteaccess-code input[type="text"] {
    width: 55px;
    margin: 0 0 0 0;
}

#siteaccess-code img {
    margin: 0 10px 0 0;
    border: 1px solid #000000;
}

.siteaccess .email_details {

}

.siteaccess_macros td {
    padding: 0 10px 0 0;
}

<?php
    global $CONFIG;
    
    $css = ".river_user_join {\n"
         . "background: url($CONFIG->url/_graphics/river_icons/river_icon_profile.gif) no-repeat left -1px;}\n"
         . ".river_user_activate {\n"
         . "background: url($CONFIG->url/_graphics/river_icons/river_icon_profile.gif) no-repeat left -1px;}\n"
         . ".river_user_admin {\n"
         . "background: url($CONFIG->url/_graphics/river_icons/river_icon_profile.gif) no-repeat left -1px;}\n"
         . "";

    echo $css;
?>
