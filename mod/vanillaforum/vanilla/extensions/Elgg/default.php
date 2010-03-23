<?php
/*
Extension Name: Elgg
Extension Url: http://elgg.org
Description: Integrates Elgg.
Works in conjunction with the Elgg Vanillaforum plugin.
Version: 0.1
Author: Kevin Jardine
Author Url: http://radagast.biz
*/

// caches the Elgg topbar in the $_$SESSION variable

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/constants.php');

function ElggTopbar_Render()
{
	global $Context, $Head;

	$elgg_url  = ELGG_URL;
    
    // cache topbar
    if (isset($_SESSION['elgg_topbar'])) {
        $elgg_topbar = $_SESSION['elgg_topbar'];
    } else {
        $elgg_topbar = '';
    }
    
    // TODO: see if there is a more concise way to check
    // if the user is logged-in
    
    $UserID = $Context->Session->GetVariable(
		    $Context->Configuration['SESSION_USER_IDENTIFIER'], 'int');
	$Head->AddStyleSheet("mod/vanillaforum/vanillaforum_css.php",'',100,$elgg_url);
	$Head->AddScript("vendors/jquery/jquery-1.2.6.pack.js",$elgg_url);   
	$Head->AddScript("mod/vanillaforum/topbar_js.php",$elgg_url);
    
    if (!$elgg_topbar && ($UserID>0)) {
        $Head->AddScript("mod/vanillaforum/get_topbar_js.php",$elgg_url);
    }
}

function ElggSendNotification($s) {
	$ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,ELGG_URL."mod/vanillaforum/notify.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$s);
    
    curl_exec ($ch);
    curl_close ($ch); 
}

function ElggDiscussionNotify($discussion_form) {
	global $Context;
	$discussion = $discussion_form->Discussion;
	$cm = $Context->ObjectFactory->NewContextObject($Context, 'CategoryManager');
	$s = array();
	$s['type'] = 'vanilla_discussion';
	$s['did'] = $discussion->DiscussionID;
	$s['category'] = $cm->GetCategoryById($discussion->CategoryID)->Name;
	$s['username'] = $Context->Session->User->Name;
	$s['name'] = $discussion->Name;
	
	ElggSendNotification($s);
}

function ElggCommentNotify($comment_form) {
	global $Context;
	$comment = $comment_form->Comment;
	$s = array();
	$s['type'] = 'vanilla_comment';
	$s['cid'] = $comment->CommentID;
	$s['did'] = $comment->DiscussionID;
	$s['category'] = $comment_form->Discussion->Category;
	$s['username'] = $Context->Session->User->Name;
	$s['name'] = $comment_form->Discussion->Name;
	
	ElggSendNotification($s);
}

$Context->AddToDelegate('Head', 'PreRender', 'ElggTopbar_Render');
$Context->AddToDelegate('DiscussionForm','PostSaveDiscussion','ElggDiscussionNotify');
$Context->AddToDelegate('DiscussionForm','PostSaveComment','ElggCommentNotify');

?>