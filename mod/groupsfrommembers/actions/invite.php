<?php
/*
    This file is part of of the groups-invite-any plugin.

    Foobar is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Foobar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with groups-invite-any.  If not, see <http://www.gnu.org/licenses/>.
*/

// Load configuration
global $CONFIG;

gatekeeper();
/* We cannot call action_gatekeeper until the groups code gets updated */
// action_gatekeeper();

$user_guid = get_input('user_guid');
if (!is_array($user_guid))
    $user_guid = array($user_guid);
$group_guid = get_input('group_guid');

if (sizeof($user_guid)) {
    foreach ($user_guid as $u_id) {
        $user = get_entity($u_id);
        $group = get_entity($group_guid);

        if ( $user && $group) {
            if ((get_loggedin_userid() == $group->owner_guid) ||
                ($group->members_invite_enable != 'no' && is_group_member($group->getGUID(), get_loggedin_userid()))) {
                if (!check_entity_relationship($group->guid, 'invited', $user->guid)) {


                // Create relationship
                    add_entity_relationship($group->guid, 'invited', $user->guid);

                    // Send email
                    if (notify_user($user->getGUID(), $group->owner_guid,
                    sprintf(elgg_echo('groups:invite:subject'), $user->name, $group->name),
                    sprintf(elgg_echo('groups:invite:body'), $user->name, $group->name, "{$CONFIG->url}action/groups/join?user_guid={$user->guid}&group_guid={$group->guid}"),
                    NULL))
                        system_message(elgg_echo("groups:userinvited"));
                    else
                        register_error(elgg_echo("groups:usernotinvited"));


                }
                else
                    register_error(elgg_echo("groups:useralreadyinvited"));
            }
            else
                register_error(elgg_echo("groups:notowner"));
        }
    }
}

forward(get_input('forward_url'));

?>