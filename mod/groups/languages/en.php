<?php
	/**
	 * Elgg projects plugin language pack
	 * 
	 * @package ElggProjects
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$english = array(
	
		/**
		 * Menu items and titles
		 */
			
			'groups' => "Projects",
			'groups:owned' => "Projects you own",
			'groups:yours' => "Your projects",
			'groups:user' => "%s's projects",
			'groups:all' => "All Dreamfish projects",
			'groups:new' => "Create a new project",
			'groups:edit' => "Edit project",
			'groups:delete' => 'Delete project',
			'groups:membershiprequests' => 'Manage join requests',
	
			'groups:icon' => 'Project icon (leave blank to leave unchanged)',
			'groups:name' => 'Project name',
			'groups:username' => 'Project short name (displayed in URLs, alphanumeric characters only)',
			'groups:description' => 'Description',
			'groups:briefdescription' => 'Brief description',
			'groups:interests' => 'Interests',
			'groups:website' => 'Website',
			'groups:members' => 'Project members',
			'groups:membership' => "Project membership permissions",
			'groups:access' => "Access permissions",
			'groups:owner' => "Owner",
	        'groups:widget:num_display' => 'Number of projects to display',
	        'groups:widget:membership' => 'Project membership',
	        'groups:widgets:description' => 'Display the projects you are a member of on your profile',
			'groups:noaccess' => 'No access to project',
			'groups:cantedit' => 'You can not edit this project',
			'groups:saved' => 'Project saved',
			'groups:featured' => 'Featured projects',
			'groups:makeunfeatured' => 'Unfeature',
			'groups:makefeatured' => 'Make featured',
			'groups:featuredon' => 'You have made this project a featured one.',
			'groups:unfeature' => 'You have removed this project from the featured list',
			'groups:joinrequest' => 'Request membership',
			'groups:join' => 'Join project',
			'groups:leave' => 'Leave project',
			'groups:invite' => 'Invite friends',
			'groups:inviteto' => "Invite friends to '%s'",
			'groups:nofriends' => "You have no friends left who have not been invited to this project.",
			'groups:viagroups' => "via projects",
			'groups:group' => "Project",
	
			'groups:notfound' => "Project not found",
			'groups:notfound:details' => "The requested project either does not exist or you do not have access to it",
			
			'groups:requests:none' => 'There are no outstanding membership requests at this time.',
	
			'item:object:groupforumtopic' => "Discussion topics",
	
			'groupforumtopic:new' => "New discussion post",
			
			'groups:count' => "projects created",
			'groups:open' => "open",
			'groups:closed' => "closed",
			'groups:member' => "members",
			'groups:searchtag' => "Search for projects by tag",
	
			
			/*
			 * Access
			 */
			'groups:access:private' => 'Closed - Users must be invited',
			'groups:access:public' => 'Open - Any user may join',
			'groups:closedgroup' => 'This project has a closed membership. To ask to be added, click the "request membership" menu link.',
	
			/*
			   Project tools
			*/
			'groups:enablepages' => 'Enable project pages',
			'groups:enableforum' => 'Enable project discussion',
			'groups:enablefiles' => 'Enable project files',
			'groups:yes' => 'yes',
			'groups:no' => 'no',
	
			'group:created' => 'Created %s with %d posts',
			'groups:lastupdated' => 'Last updated %s by %s',
			'groups:pages' => 'Project pages',
			'groups:files' => 'Project files',
	
			/*
			  Project forum strings
			*/
			
			'group:replies' => 'Replies',
			'groups:forum' => 'Project discussion',
			'groups:addtopic' => 'Add a topic',
			'groups:forumlatest' => 'Latest discussion',
			'groups:latestdiscussion' => 'Latest discussion',
			'groups:newest' => 'Newest',
			'groups:popular' => 'Popular',
			'groupspost:success' => 'Your comment was succesfully posted',
			'groups:alldiscussion' => 'Latest discussion',
			'groups:edittopic' => 'Edit topic',
			'groups:topicmessage' => 'Topic message',
			'groups:topicstatus' => 'Topic status',
			'groups:reply' => 'Post a comment',
			'groups:topic' => 'Topic',
			'groups:posts' => 'Posts',
			'groups:lastperson' => 'Last person',
			'groups:when' => 'When',
			'grouptopic:notcreated' => 'No topics have been created.',
			'groups:topicopen' => 'Open',
			'groups:topicclosed' => 'Closed',
			'groups:topicresolved' => 'Resolved',
			'grouptopic:created' => 'Your topic was created.',
			'groupstopic:deleted' => 'The topic has been deleted.',
			'groups:topicsticky' => 'Sticky',
			'groups:topicisclosed' => 'This topic is closed.',
			'groups:topiccloseddesc' => 'This topic has now been closed and is not accepting new comments.',
			'grouptopic:error' => 'Your project topic could not be created. Please try again or contact a system administrator.',
			'groups:forumpost:edited' => "You have successfully edited the forum post.",
			'groups:forumpost:error' => "There was a problem editing the forum post.",
			'groups:privategroup' => 'This project is private, requesting membership.',
			'groups:notitle' => 'Projects must have a title',
			'groups:cantjoin' => 'Can not join project',
			'groups:cantleave' => 'Could not leave project',
			'groups:addedtogroup' => 'Successfully added the user to the project',
			'groups:joinrequestnotmade' => 'Join request could not be made',
			'groups:joinrequestmade' => 'Request to join project successfully made',
			'groups:joined' => 'Successfully joined project!',
			'groups:left' => 'Successfully left project',
			'groups:notowner' => 'Sorry, you are not the owner of this project.',
			'groups:alreadymember' => 'You are already a member of this project!',
			'groups:userinvited' => 'User has been invited.',
			'groups:usernotinvited' => 'User could not be invited.',
			'groups:useralreadyinvited' => 'User has already been invited',
			'groups:updated' => "Last comment",
			'groups:invite:subject' => "%s you have been invited to join %s!",
			'groups:started' => "Started by",
			'groups:joinrequest:remove:check' => 'Are you sure you want to remove this join request?',
			'groups:invite:body' => "Hi %s,

You have been invited to join the '%s' project, click below to confirm:

%s",

			'groups:welcome:subject' => "Welcome to the %s project!",
			'groups:welcome:body' => "Hi %s!
		
You are now a member of the '%s' project! Click below to begin posting!

%s",
	
			'groups:request:subject' => "%s has requested to join %s",
			'groups:request:body' => "Hi %s,

%s has requested to join the '%s' project, click below to view their profile:

%s

or click below to confirm request:

%s",
	
            /*
				Forum river items
			*/
	
			'groups:river:member' => 'is now a member of',
			'groupforum:river:updated' => '%s has updated',
			'groupforum:river:update' => 'this discussion topic',
			'groupforum:river:created' => '%s has created',
			'groupforum:river:create' => 'a new discussion topic titled',
			'groupforum:river:posted' => '%s has posted a new comment',
			'groupforum:river:annotate:create' => 'on this discussion topic',
			'groupforum:river:postedtopic' => '%s has started a new discussion topic titled',
			'groups:river:member' => '%s is now a member of',
	
			'groups:nowidgets' => 'No widgets have been defined for this project.',
	
	
			'groups:widgets:members:title' => 'Project members',
			'groups:widgets:members:description' => 'List the members of a project.',
			'groups:widgets:members:label:displaynum' => 'List the members of a project.',
			'groups:widgets:members:label:pleaseedit' => 'Please configure this widget.',
	
			'groups:widgets:entities:title' => "Objects in project",
			'groups:widgets:entities:description' => "List the objects saved in this project",
			'groups:widgets:entities:label:displaynum' => 'List the objects of a project.',
			'groups:widgets:entities:label:pleaseedit' => 'Please configure this widget.',
		
			'groups:forumtopic:edited' => 'Forum topic successfully edited.',
	
			/**
			 * Action messages
			 */
			'group:deleted' => 'Project and project contents deleted',
			'group:notdeleted' => 'Project could not be deleted',
	
			'grouppost:deleted' => 'Project posting successfully deleted',
			'grouppost:notdeleted' => 'Project posting could not be deleted',
			'groupstopic:deleted' => 'Topic deleted',
			'groupstopic:notdeleted' => 'Topic not deleted',
			'grouptopic:blank' => 'No topic',
			'groups:deletewarning' => "Are you sure you want to delete this project? There is no undo!",
	
			'groups:joinrequestkilled' => 'The join request has been deleted.',
	);
					
	add_translation("en",$english);
?>
