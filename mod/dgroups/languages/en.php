<?php
	/**
	 * Elgg groups plugin language pack
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$english = array(
	
		/**
		 * Menu items and titles
		 */
			
			'dgroups' => "groups",
			'dgroups:owned' => "groups you own",
			'dgroups:yours' => "Your group",
			'dgroups:user' => "%s's group",
			'dgroups:all' => "All site groups",
			'dgroups:new' => "Create a new group",
			'dgroups:edit' => "Edit group",
			'dgroups:delete' => 'Delete group',
			'dgroups:membershiprequests' => 'Manage join requests',
	
			'dgroups:icon' => 'group icon (leave blank to leave unchanged)',
			'dgroups:name' => 'group name',
			'dgroups:username' => 'group short name (displayed in URLs, alphanumeric characters only)',
			'dgroups:description' => 'Description',
			'dgroups:briefdescription' => 'Brief description',
			'dgroups:interests' => 'Interests',
			'dgroups:website' => 'Website',
			'dgroups:members' => 'group members',
			'dgroups:membership' => "group membership permissions",
			'dgroups:access' => "Access permissions",
			'dgroups:owner' => "Owner",
	        'dgroups:widget:num_display' => 'Number of groups to display',
	        'dgroups:widget:membership' => 'group membership',
	        'dgroups:widgets:description' => 'Display the groups you are a member of on your profile',
			'dgroups:noaccess' => 'No access to group',
			'dgroups:cantedit' => 'You can not edit this group',
			'dgroups:saved' => 'group saved',
			'dgroups:featured' => 'Featured groups',
			'dgroups:makeunfeatured' => 'Unfeature',
			'dgroups:makefeatured' => 'Make featured',
			'dgroups:featuredon' => 'You have made this group a featured one.',
			'dgroups:unfeature' => 'You have removed this group from the featured list',
			'dgroups:joinrequest' => 'Request membership',
			'dgroups:join' => 'Join group',
			'dgroups:leave' => 'Leave group',
			'dgroups:invite' => 'Invite friends',
			'dgroups:inviteto' => "Invite friends to '%s'",
			'dgroups:nofriends' => "You have no friends left who have not been invited to this group.",
			'dgroups:viadgroups' => "via groups",
			'dgroups:dgroup' => "group",
	
			'dgroups:notfound' => "group not found",
			'dgroups:notfound:details' => "The requested group either does not exist or you do not have access to it",
			
			'dgroups:requests:none' => 'There are no outstanding membership requests at this time.',
	
			'item:object:dgroupforumtopic' => "Discussion topics",
	
			'dgroupforumtopic:new' => "New discussion post",
			
			'dgroups:count' => "groups created",
			'dgroups:open' => "open group",
			'dgroups:closed' => "closed group",
			'dgroups:member' => "members",
			'dgroups:searchtag' => "Search for groups by tag",
	
			
			/*
			 * Access
			 */
			'dgroups:access:private' => 'Closed - Users must be invited',
			'dgroups:access:public' => 'Open - Any user may join',
			'dgroups:closeddgroup' => 'This group has a closed membership. To ask to be added, click the "request membership" menu link.',
	
			/*
			   group tools
			*/
			'dgroups:enablepages' => 'Enable group pages',
			'dgroups:enableforum' => 'Enable group discussion',
			'dgroups:enablefiles' => 'Enable group files',
			'dgroups:yes' => 'yes',
			'dgroups:no' => 'no',
	
			'dgroup:created' => 'Created %s with %d posts',
			'dgroups:lastupdated' => 'Last updated %s by %s',
			'dgroups:pages' => 'group pages',
			'dgroups:files' => 'group files',
	
			/*
			  group forum strings
			*/
			
			'dgroup:replies' => 'Replies',
			'dgroups:forum' => 'group discussion',
			'dgroups:addtopic' => 'Add a topic',
			'dgroups:forumlatest' => 'Latest discussion',
			'dgroups:latestdiscussion' => 'Latest discussion',
			'dgroups:newest' => 'Newest',
			'dgroups:popular' => 'Popular',
			'dgroupspost:success' => 'Your comment was succesfully posted',
			'dgroups:alldiscussion' => 'Latest discussion',
			'dgroups:edittopic' => 'Edit topic',
			'dgroups:topicmessage' => 'Topic message',
			'dgroups:topicstatus' => 'Topic status',
			'dgroups:reply' => 'Post a comment',
			'dgroups:topic' => 'Topic',
			'dgroups:posts' => 'Posts',
			'dgroups:lastperson' => 'Last person',
			'dgroups:when' => 'When',
			'dgrouptopic:notcreated' => 'No topics have been created.',
			'dgroups:topicopen' => 'Open',
			'dgroups:topicclosed' => 'Closed',
			'dgroups:topicresolved' => 'Resolved',
			'dgrouptopic:created' => 'Your topic was created.',
			'dgroupstopic:deleted' => 'The topic has been deleted.',
			'dgroups:topicsticky' => 'Sticky',
			'dgroups:topicisclosed' => 'This topic is closed.',
			'dgroups:topiccloseddesc' => 'This topic has now been closed and is not accepting new comments.',
			'dgrouptopic:error' => 'Your group topic could not be created. Please try again or contact a system administrator.',
			'dgroups:forumpost:edited' => "You have successfully edited the forum post.",
			'dgroups:forumpost:error' => "There was a problem editing the forum post.",
			'dgroups:privatedgroup' => 'This group is private, requesting membership.',
			'dgroups:notitle' => 'groups must have a title',
			'dgroups:cantjoin' => 'Can not join group',
			'dgroups:cantleave' => 'Could not leave group',
			'dgroups:addedtodgroup' => 'Successfully added the user to the group',
			'dgroups:joinrequestnotmade' => 'Join request could not be made',
			'dgroups:joinrequestmade' => 'Request to join group successfully made',
			'dgroups:joined' => 'Successfully joined group!',
			'dgroups:left' => 'Successfully left group',
			'dgroups:notowner' => 'Sorry, you are not the owner of this group.',
			'dgroups:alreadymember' => 'You are already a member of this group!',
			'dgroups:userinvited' => 'User has been invited.',
			'dgroups:usernotinvited' => 'User could not be invited.',
			'dgroups:useralreadyinvited' => 'User has already been invited',
			'dgroups:updated' => "Last comment",
			'dgroups:invite:subject' => "%s you have been invited to join %s!",
			'dgroups:started' => "Started by",
			'dgroups:joinrequest:remove:check' => 'Are you sure you want to remove this join request?',
			'dgroups:invite:body' => "Hi %s,

You have been invited to join the '%s' group, click below to confirm:

%s",

			'dgroups:welcome:subject' => "Welcome to the %s group!",
			'dgroups:welcome:body' => "Hi %s!
		
You are now a member of the '%s' group! Click below to begin posting!

%s",
	
			'dgroups:request:subject' => "%s has requested to join %s",
			'dgroups:request:body' => "Hi %s,

%s has requested to join the '%s' group, click below to view their profile:

%s

or click below to confirm request:

%s",
	
            /*
				Forum river items
			*/
	
			'dgroups:river:member' => 'is now a member of',
			'dgroupforum:river:updated' => '%s has updated',
			'dgroupforum:river:update' => 'this discussion topic',
			'dgroupforum:river:created' => '%s has created',
			'dgroupforum:river:create' => 'a new discussion topic titled',
			'dgroupforum:river:posted' => '%s has posted a new comment',
			'dgroupforum:river:annotate:create' => 'on this discussion topic',
			'dgroupforum:river:postedtopic' => '%s has started a new discussion topic titled',
			'dgroups:river:member' => '%s is now a member of',
	
			'dgroups:nowidgets' => 'No widgets have been defined for this group.',
	
	
			'dgroups:widgets:members:title' => 'group members',
			'dgroups:widgets:members:description' => 'List the members of a group.',
			'dgroups:widgets:members:label:displaynum' => 'List the members of a group.',
			'dgroups:widgets:members:label:pleaseedit' => 'Please configure this widget.',
	
			'dgroups:widgets:entities:title' => "Objects in group",
			'dgroups:widgets:entities:description' => "List the objects saved in this group",
			'dgroups:widgets:entities:label:displaynum' => 'List the objects of a group.',
			'dgroups:widgets:entities:label:pleaseedit' => 'Please configure this widget.',
		
			'dgroups:forumtopic:edited' => 'Forum topic successfully edited.',
	
			/**
			 * Action messages
			 */
			'dgroup:deleted' => 'group and group contents deleted',
			'dgroup:notdeleted' => 'group could not be deleted',
	
			'dgrouppost:deleted' => 'group posting successfully deleted',
			'dgrouppost:notdeleted' => 'group posting could not be deleted',
			'dgroupstopic:deleted' => 'Topic deleted',
			'dgroupstopic:notdeleted' => 'Topic not deleted',
			'dgrouptopic:blank' => 'No topic',
			'dgroups:deletewarning' => "Are you sure you want to delete this group? There is no undo!",
	
			'dgroups:joinrequestkilled' => 'The join request has been deleted.',
	);
					
	add_translation("en",$english);
?>