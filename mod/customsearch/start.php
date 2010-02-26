<?php
 
function customsearch_init() 
{
  //register_plugin_hook('search','','new_search');

  /* extend_view('metatags','customindex/metatags');

  eregister_plugin_hook('search'xtend_view('css','customindex/css'); */

  //register_page_handler('search','new_search');

  register_plugin_hook('search', 'all', 'new_search');

  //register_page_handler('search','new_search_pagehandler');

} 

	
function new_search() 
{


  if (!@include_once(dirname(__FILE__) . "/index.php")) 
  {
    return false;
  }

  return true;
}


function new_search_pagehandler($page)
{
	global $CONFIG;
		
  @include(dirname(__FILE__) . "/index.php");
  
 return true;
}

function get_all_subtypes(){
	
	global $CONFIG;
	

	$rows = get_data("SELECT subtype as regsubtypes FROM {$CONFIG->dbprefix}entity_subtypes ");
	
	return $rows;
}
function trim_array($array){
$newarray= array();
$i = 0;
foreach ($array as $key => $value) {
  if (!empty($value)) {
    $newarray[$i] = $value;
    $i++;
  }
}
return $newarray;
}
register_elgg_event_handler('init', 'system', 'customsearch_init');

?>