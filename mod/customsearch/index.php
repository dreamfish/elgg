<?php
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
global $CONFIG;
set_context('search');
$searchType      = get_input('searchType');
$searchstring    = get_input('tag');
$tag             = $searchstring;
$page_size       = 10;
$search_min_size = 2;

 /* side bar menus */
$CONFIG->submenu['a'] = '';       
add_submenu_item('Users', $CONFIG->wwwroot . "search/?tag=" . urlencode($tag) . "&searchType=users");
add_submenu_item(elgg_echo('Tags'), $CONFIG->wwwroot . "search/?tag=" . urlencode($tag) . "&searchType=tags");
add_submenu_item(elgg_echo('Fulltext'), $CONFIG->wwwroot . "search/?tag=" . urlencode($tag) . "&searchType=fulltext");

$displayArray = array();
$arrayCount   = 0;



/* pagination */
function display_pager($current_page, $number_of_results, $results_per_page, $url)
{      
  $page_range = 8;

  /** Check if the pager is requested */
  if ($results_per_page >= $number_of_results or !$current_page) 
  {
    return '';
  }

  /** Count the pages */
  $number_of_pages = ceil($number_of_results/$results_per_page);
  $return = array();

  /** No need for pagination */
  if ($number_of_pages == 1) 
  {
    return '';
  }
  
  $return = '<div class="pagination">';
    
  switch($current_page) 
  {

    /** Case if the current page is the first one */
    case 1: 
    
      /** Counting forward */
      for($i = $current_page; $i <= $current_page + $page_range; $i++) 
      {

        if ($i > $number_of_pages) 
        {
          continue;
        }

        if ($i == 1)
        {
          /** Display no link for current page*/
          $return .= '<span class="pagination_currentpage"> 1 </span>';
        }
        else
        {               
          $return .= '<a href="' . $url . '&page=' . $i . '" class="pagination_number"> ' . $i . ' </a>';
    
        }

      }
      
      /** Setting the next page */
      $return .= '<a href="' . $url . '&page=2" class="pagination_next">Next &raquo;</a>';
      
    break;

    /** Case if the current page is the last one */             
    case $number_of_pages:

      /** Setting the previous page */ 
      $return .= '<a href="' . $url . '&page=' . ($current_page - 1) . '" class="pagination_number"> &laquo; Previous </a>';

      /** Counting backward */ 
      for($i = $current_page - $page_range; $i <= $current_page; $i++) 
      {
        if ($i < 1) 
        {
          continue;
        }
        
        if ($i == $number_of_pages)
        {
          /** Display no link for current page*/
          $return .= '<span class="pagination_currentpage"> ' . $number_of_pages . ' </span>';
        }
        else
        {               
          $return .= '<a href="' . $url . '&page=' . $i . '" class="pagination_number"> ' . $i . ' </a>';
           
        }
       
      }
      break;

    /** Different case */
    default: 
    
      /** Setting the previous page */ 
      $return .= '<a href="' . $url . '&page=' . ($current_page - 1) . '" class="pagination_number"> &laquo; Previous </a>';

      /** Counting backward */ 
      for($i = $current_page - $page_range/2; $i < $current_page; $i++) 
      {
        if ($i < 1) 
        {
          continue;
        }
        
        $return .= '<a href="' . $url . '&page=' . $i . '" class="pagination_number"> ' . $i . ' </a>';     
      }
        
      /** Counting forward */
      for($i = $current_page; $i <= $current_page + $page_range; $i++) 
      {
        if ($i > $number_of_pages) 
        {
          continue;
        }
        
        if ($i == $current_page)
        {
          /** Display no link for current page*/
          $return .= '<span class="pagination_currentpage"> ' . $current_page . ' </span>';
        }
        else
        {
          $return .= '<a href="' . $url . '&page=' . $i . '" class="pagination_number"> ' . $i . ' </a>';   
        }
        
      }

      /** Setting the next page */ 
      $return .= '<a href="' . $url . '&page=' . ($current_page + 1) . '" class="pagination_next">Next &raquo;</a>';
      
      break;
  }

  $return .= '<br class="clearfloat" /></div>';
  
  return $return;
}            

                   
if(!empty($tag) and strlen($tag) >= $search_min_size)
{
  
$i=0;
	if($searchType == 'fulltext')
  {    
    $body          = "";
    $allowedTypes  = array();
    
    $allowedT=get_all_subtypes();
   foreach ($allowedT as $key=>$subT) {
   	
   	//echo ""
  //	print_r($subT);
  foreach($subT as $k=>$v)
  {
  	
  	$allowedTypes[$i]=$v;
  	$i++;
  }
   	
   	
   	
   }
    
    // $allowedTypes  = array("groupforumtopic", "blog", "file", "page_top", "bookmarks", "page", "event_calendar");
    $object_types  = get_registered_entity_types();

    $results_found = array();
    
    //get_metadata_byname();
    
    $rows = get_data("SELECT a1.*,a2.* 
                      FROM {$CONFIG->dbprefix}metastrings as a1, {$CONFIG->dbprefix}metadata as a2  
                      WHERE a1.string LIKE '%{$searchstring}%' AND a2.value_id=a1.id
                      GROUP BY a2.entity_guid");
   

			
  if(!empty($rows))
  {
    $i=0;
    
    foreach($rows as $row)
    {

      $entity_id = $row->entity_guid;
      $entities  = get_entity($entity_id);
      
      $displayArray[$arrayCount] = $entity_id;
      $arrayCount++;

      if((in_array(get_subtype_from_id($entities->subtype), $allowedTypes)) or (empty($entities->subtype)))
      {

        if($entities->type != 'site')
        {
        		if(!(in_array($entities->guid,$added))){
        	
        	
          $added[$i] = $entities->guid;
          $i++;
          
          if(get_subtype_from_id($entities->subtype) == 'event_calendar')
          {
            $results_found[] = "<div class=\"search_listing\">" . elgg_view_entity($entities, $fullview) . "</div>";
          } 
          else
          {
            $results_found[] = elgg_view_entity($entities, $fullview);
          }
          
        }
          
        }
        
      }

    }

  } 


  $rowsA = get_data("SELECT a1.*,a2.* 
                     FROM {$CONFIG->dbprefix}metastrings as a1, {$CONFIG->dbprefix}annotations as a2  
                     WHERE a1.string LIKE '%{$searchstring}%' AND a2.value_id=a1.id 
                     GROUP BY a2.entity_guid");

  if(!empty($rowsA))
  {	
    $i=0;
    
    foreach($rowsA as $rowA)
    {

      $entity_idA = $rowA->entity_guid;
      $entitiesA  = get_entity($entity_idA);
      
      if((in_array(get_subtype_from_id($entitiesA->subtype), $allowedTypes)) or (empty($entitiesA->subtype)))
      {

        if($entitiesA->type != 'site')
        {
        	if(!(in_array($entities->guid,$added))){

          $added[$i] = $entitiesA->guid;
          $i++;
          
          if(get_subtype_from_id($entitiesA->subtype) == 'event_calendar')
          {
            $results_found[] = "<div class=\"search_listing\">" . elgg_view_entity($entitiesA, $fullview) . "</div>";
          } 
          else
          {
            $results_found[] = elgg_view_entity($entitiesA, $fullview);
          }
        	}
          
        }
        
      }
    }
  }

  $rows2 = get_data("SELECT * FROM {$CONFIG->dbprefix}objects_entity WHERE  title LIKE  '%{$searchstring}%' OR description LIKE '%{$searchstring}%'");

  if(!empty($rows2))
  {
    
    foreach($rows2 as $row2)
    {

      $entity_id2 =$row2->guid;
      $entities2  = get_entity($entity_id2);
      
      if((in_array(get_subtype_from_id($entities2->subtype), $allowedTypes)) or (empty($entities2->subtype)))
      {  
        
        if(!in_array($entities2->guid, $added)) 
        {
          $added[$i] = $entitiesA->guid;
          $i++;
          if($entities2->type != 'site')
          {
            
            if(get_subtype_from_id($entities2->subtype) == 'event_calendar')
            {
              $results_found[] = "<div class=\"search_listing\">" . elgg_view_entity($entities2, $fullview) . "</div>";
            } 
            else
            {
              $results_found[] = elgg_view_entity($entities2, $fullview);
            }
            
          }
          
        }   

      }
      
    }
    
  }

  $rows3 = get_data("SELECT * FROM {$CONFIG->dbprefix}groups_entity WHERE  name LIKE  '%{$searchstring}%' OR description LIKE '%{$searchstring}%'");

  if(!empty($rows3))
  {
    foreach($rows3 as $row3)
    {

      $entity_id3 = $row3->guid;
      $entities3  = get_entity($entity_id3);
      
      if((in_array(get_subtype_from_id($entities3->subtype), $allowedTypes)) or (empty($entities3->subtype)))
      { 
       
        if(!in_array($entities3->guid, $added)) 
        {
          
          if($entities3->type != 'site')
          {
            
            if(get_subtype_from_id($entities3->subtype) == 'event_calendar')
            {
              $results_found[] = "<div class=\"search_listing\">" . elgg_view_entity($entities3, $fullview) . "</div>";
            } 
            else
            {
              $results_found[] = elgg_view_entity($entities3, $fullview);
            }
            
          }
          
        }   

      }

    }
    
  }
  
  $url          = '?tag' . $tag . '&searchType=' . $searchType;
  $current_page = get_input('page');
  
  $current_page = intval($current_page);
  $current_page = ($current_page) ? $current_page : 1;;
  
  $results_found=trim_array($results_found);  
 // array_unique($results_found);
  $number_of_results = count($results_found);
 
  if ($number_of_results)
  {
    $offset         = ($current_page) ? ($current_page - 1) : 0;
    $results_sliced = array_slice($results_found, $offset * $page_size, $page_size);

    $html = implode('', $results_sliced);
  }
  else
  {
    $html = elgg_echo('content:search:no-result');
  }
  
  $pagination = display_pager($current_page, $number_of_results, $page_size, '?tag=' . $tag . '&searchType=' . $searchType);

  $results_found_message = '';
  if ($number_of_results)
  {
    $results_found_message = elgg_echo('content:search:results-found') . $number_of_results;
  }
  
  $body = $pagination . $html . $pagination . $results_found_message;

  } 
  else
  {

    if($searchType == 'users')
    {
       	
    	
       $tag        = get_input('tag');
      $objecttype = 'user';  
      $title      = 'users';

      // $pagination = display_pager($current_page, $number_of_results, $page_size, '?tag=' . $tag . '&searchType=' . $searchType);
      
      if (!empty($tag)) 
      {
        $body = list_user_search($tag,$page_size);
      }

    } 
    elseif ($searchType == 'tags')
    {		
      if (!empty($tag)) 
      {
        $body  = "";
			
        //$meta_name, $meta_value = "", $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = true, $pagination = true) {
			$body .= list_entities_from_metadata('tags', $tag, $objecttype, $subtype, $owner_guid_array, $page_size, false, false,true);
         

      }
      
    }

  }
  
} 

else 
{	 
  $body = elgg_echo(empty($tag) ? 'content:search:no-result' : 'content:search:too-short');
}

 //$body = $body;
$body = elgg_view_layout('two_column_left_sidebar', '', $body);

page_draw(elgg_echo('search'), $body);

		
?>