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

$users = $vars['users'];
$nusers = $vars['nusers'];
$group = $vars['entity'];
$owner = get_entity($vars['entity']->owner_guid);
$forward_url = $group->getURL();

$nmembers = (int)get_entities('user', '', 0, '', 0, 0, true);
$maxusers = (int)get_plugin_setting('maxusers', 'groupsfrommembers');
if ($nmembers <= $maxusers && $users == null) {
    ?>
<div class="contentWrapper">
    <form action="<?php echo $vars['url']; ?>action/groups/invite" method="post">

            <?php
            if (($members = get_entities('user', '', 0, '', $maxusers)) != null) {
                echo elgg_view('friends/picker', array('entities' => $members, 'internalname' => 'user_guid', 'highlight' => 'all'));
            }

            ?>
        <input type="hidden" name="forward_url" value="<?php echo $forward_url; ?>" />
        <input type="hidden" name="group_guid" value="<?php echo $group->guid; ?>" />
        <input type="submit" value="<?php echo elgg_echo('invite'); ?>" />
    </form>
</div>
<?php
} else {
    echo elgg_view('groupsfrommembers/search', array('entity' => $group->getGUID()));
    if ($users) {
        if ($nusers > get_plugin_setting('maxusers', 'groupsfrommembers')) {
            echo sprintf(elgg_echo('groupsfrommembers:toomany'), get_plugin_setting('maxusers', 'groupsfrommembers')) . '<br />';
        }
        $context = get_context();
        set_context('search');
        $body = '';
        //$options = array();
        foreach ($users as $user) {
        //$options[$user->username] = $user->getGUID();
        //$options[elgg_view_entity($user, false)] = $user->getGUID();
            $body .= '<div>';
            //$body .= '<input style="float:left" class="input-checkboxes" type="checkbox" value="' . $user->getGUID() . '" name="user_guid[]" />';
            $body .= '<input style="display: inline-block; vertical-align: middle" class="input-checkboxes" type="checkbox" value="' . $user->getGUID() . '" name="user_guid[]" />';
            $body .= '<div style="display: inline-block; vertical-align: middle">' . elgg_view_entity($user, false) . '</div>';
            $body .= '</div>';
        }
        //$body .= elgg_view('input/checkboxes', array('internalname' => 'user_guid', 'options' => $options));
        $body .= elgg_view('input/hidden', array('internalname' => 'group_guid', 'value' => $group->getGUID()));
        $body .= elgg_view('input/hidden', array('internalname' => 'forward_url', 'value' => $group->getURL()));
        $body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('invite')));

        echo elgg_view('input/form', array('body' => $body, 'action' => $vars['url'] . 'action/groups/invite'));

        set_context($context);
    }
}
?>