<?php
/*
    This file is part of of the groups-from-members plugin.

    Foobar is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Foobar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with groups-from-members.  If not, see <http://www.gnu.org/licenses/>.
*/

function groups_from_members_member_can_invite($hook_name, $entity_type, $return_value, $parameters) {
    if (get_context() == 'groupsfrommembers' && $entity_type == 'group') {
        $group = $parameters['entity'];
        $user = $parameters['user'];

        if ($group->members_invite_enable != 'no') {
            if (is_group_member($group->getGUID(), $user->getGUID())) {
                return true;
            }
        }
    }

    return $return_value;
}

function groups_from_members_submenus() {
    global $CONFIG;

    $page_owner = page_owner_entity();

    // Submenu items for all group pages
    if ($page_owner instanceof ElggGroup && get_context() == 'groups' && get_loggedin_userid() != $page_owner->getOwner()) {
        if (isloggedin() && !isadminloggedin()) {
            $context = get_context();
            set_context('groupsfrommembers');
            if ($page_owner->canEdit()) {
                add_submenu_item(elgg_echo('groups:invite'),$CONFIG->wwwroot . "mod/groupsfrommembers/invite.php?group_guid={$page_owner->getGUID()}", '1groupsactions');
                if (!$page_owner->isPublicMembership())
                    add_submenu_item(elgg_echo('groups:membershiprequests'),$CONFIG->wwwroot . "mod/groups/membershipreq.php?group_guid={$page_owner->getGUID()}", '1groupsactions');
            }
            set_context($context);
        }
    }
}

function groups_from_members_member_invited_action($hook_name, $entity_type, $return_value, $parameters) {
    global $CONFIG;

    $return_value = false; // Do not call the real thing TM

    include $CONFIG->pluginspath . '/groupsfrommembers/actions/invite.php';

    return $return_value;
}

function groups_from_members_init() {
    global $CONFIG;

    add_group_tool_option('members_invite', elgg_echo('groupsfrommembers:members-invite'), false);

    if (get_plugin_setting('maxusers') == 0) {
        set_plugin_setting('maxusers', 20);
    }
    register_plugin_hook('permissions_check', 'group', 'groups_from_members_member_can_invite');
    register_elgg_event_handler('pagesetup','system','groups_from_members_submenus');
    register_plugin_hook('action', 'groups/invite', 'groups_from_members_member_invited_action');

    register_action('groupsfrommembers/search', false, $CONFIG->pluginspath . 'groupsfrommembers/actions/search.php');
}

register_elgg_event_handler('init', 'system', 'groups_from_members_init');
?>
