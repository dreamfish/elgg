<?php
    $context = $vars['context'];
    $offset = $vars['offset'];
    $entities = $vars['entities'];
    $limit = $vars['limit'];
    $count = $vars['count'];
    $baseurl = $vars['baseurl'];
    $pagination = $vars['pagination'];

    $html = "";
    $nav = "";

    $ts = time();
    $token = generate_action_token($ts);
    $show = get_input('show');

    if ($pagination)
	$nav .= elgg_view('navigation/pagination',array(
	    'baseurl' => $baseurl,
            'offset' => $offset,
            'count' => $count,
            'limit' => $limit,
            ));

    $html .= $nav;

    if (is_array($entities) && sizeof($entities) > 0) {
	foreach($entities as $entity) {	
	    $html .= elgg_view('siteaccess/user_view', array('entity' => $entity, 'ts' => $ts, 'token' => $token, 'show' => $show));
	}
    }

    echo $html;
?>
