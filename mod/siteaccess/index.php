<?php
    require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

    admin_gatekeeper();
    set_context('admin');

    set_page_owner($_SESSION['guid']);

    $offset = get_input('offset');
    $limit = 10;
    $context = get_context();

    $show = get_input('show');
    switch($show) {
        case 'invited':
            $friend_user  = get_input('friend_username');
            if ($friend_user = get_user_by_username($friend_user)) {
                $meta_name = 'invited_by_guid';
                $meta_value = $friend_user->guid;
            }
        break;
        case 'banned':
            $meta_name = 'ban_reason';
            $meta_value = 'banned';
        break;
        case 'activate':
            $meta_name = 'validated';
            $meta_value = '0';
        break;
        case 'validate':
            $meta_name = 'validated_email';
            $meta_value = '0';
        break;
    }

    $title = elgg_view_title(elgg_echo('siteaccess:admin:menu'));
    $html = "";
    if ($show == 'templates') {
        $html .= elgg_view('siteaccess/menu', array('show' => $show));
        $html .= elgg_view('siteaccess/templates');
    } else {
        $count = siteaccess_count_users($meta_name, $meta_value);
        $entities = siteaccess_users($meta_name, $meta_value, $limit, $offset);
        $html .= elgg_view('siteaccess/menu', array('count' => $count, 'show' => $show));
        $html .= elgg_view('siteaccess/user_list',
            array(
                'entities' => $entities,
                'count' => $count,
                'offset' => $offset,
                'limit' => $limit,
                'baseurl' => $_SERVER['REQUEST_URI'],
                'context' => $context,
                'pagination' => true,
                'friend_guid' => $friend_guid
                ));
    }
    $body = elgg_view('page_elements/contentwrapper', array('body' => $html, 'subclass' => 'siteaccess'));

    page_draw(elgg_echo('siteaccess:admin:menu'),elgg_view_layout("two_column_left_sidebar", '', $title . $body));
?>
