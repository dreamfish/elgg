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

gatekeeper();
action_gatekeeper();

$group = get_entity(get_input('entity'));

set_page_owner($group->getGUID());
set_context('groups');

$name = get_input('name');

if ($name) {
    $users = search_for_user($name, get_plugin_setting('maxusers', 'groupsfrommembers'));
    $nusers = search_for_user($name, 0, 0, '', true);
}

$title = elgg_echo("groups:invite");

$area2 = elgg_view_title($title);

$context = get_context();
set_context('groupsfrommembers');
if (($group) && ($group->canEdit())) {
    $area2 .= elgg_view('forms/groups/invite', array('entity' => $group, 'users' => $users, 'nusers' => $nusers));
} else {
    $area2 .= elgg_echo("groups:noaccess");
}
set_context($context);

$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);

page_draw($title, $body);

?>