<?php
/**
 * Elgg blogextended plugin
 *
 * @package ElggBlogExtended
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2009
 * @link http://www.somosmas.org
 */

/**
 * Blog extended initialization
 *
 * Register css extensions, contentes view for groups, widgets and event handlers
 */

function blogextended_init(){
  global $CONFIG;
  extend_view("css","blogextended/css");

  extend_view("blog/fields_before","blog/forms/type");
  extend_view("blog/fields_before","groups/groupselector");

  extend_view('groups/left_column', 'groups/groupcontents',1);

  add_widget_type('blog',elgg_echo('blog:widget:title'), elgg_echo('blog:widget:description'));

  register_elgg_event_handler("create","object","blog_type_handler");
  register_elgg_event_handler("update","object","blog_type_handler");

  register_elgg_event_handler("create","object","group_selector_handler");
  register_elgg_event_handler("update","object","group_selector_handler");
  register_page_handler('gblog','gblog_page_handler');
  if(is_plugin_enabled("itemicon")){
  	if(!isset($CONFIG->itemicon)){
  	  $CONFIG->itemicon[]=array();
  	}
  	$CONFIG->itemicon[] = "blog";
    extend_view("blog/fields_after","itemicon/add");
  }

  
  $options = array(
  				""=>"All",
  				"HowTo"=>"How To's",
  				"WorkStories"=>"Work Stories",
  				"ManagingProjects"=>"Managing Projects",
  );

  
  $CONFIG->BLOG_TYPES = $options;
  
  if(file_exists(dirname(__FILE__)."/config.php")){
    @require_once dirname(__FILE__)."/config.php";
  }

  $CONFIG->blogextended = $options;
}


function gblog_page_handler($page) {
	
	// The second part dictates what we're doing
	if (isset($page[0])) {
		switch($page[0]) {
			case "read":		set_input('blogpost',$page[1]);
								include(dirname(__FILE__) . "/read.php"); return true;
								break;
		}
	// If the URL is just 'blog/username', or just 'blog/', load the standard blog index
	} else {
		@include(dirname(__FILE__) . "/index.php");
		return true;
	}
	
	return false;
	
}

/**
 * Blog type handler. Sets the blog type property
 *
 * @param string $event create | update
 * @param string $object_type object
 * @param object $object Blog object
 * @return boolean
 */
function blog_type_handler($event, $object_type, $object){
  if($object->getSubtype()=="blog"){
    $blog_type = get_input("blog_type");
    switch($event){
      case "create":
      case "update":
        $object->clearMetadata("blog_type");
        $object->set("blog_type",$blog_type);;
        if(!empty($type)){
          //Registering metadata in all the registered languages for easy localized search
          $translations = get_installed_translations();
          foreach($translations as $key=>$value){
            $var = "blog_type_{$key}";
            $object->clearMetadata($var);
            $object->set($var,elgg_echo($blog_type,$key));
          }
        }
        break;
    }
  }
  return true;
}

/**
 * Group selector handler
 *
 * Sets the selected group as content_owner for the selected post
 * @param string $event create | update
 * @param string $object_type object
 * @param object $object Blog object
 * @return boolean
 */
function group_selector_handler($event, $object_type, $object){
  $subtype = $object->getSubtype();
  if($subtype=="blog"){
    $content_owner = get_input("content_owner");
    if(!empty($content_owner)){
      switch($event){
        case "create":
        case "update":		  
		      $object->set("container_guid", $content_owner->guid);
          $object->clearMetadata("content_owner");
          $object->set("content_owner",$content_owner);
          break;
      }
    }
  }
  return true;
}

register_elgg_event_handler('init','system','blogextended_init');

?>