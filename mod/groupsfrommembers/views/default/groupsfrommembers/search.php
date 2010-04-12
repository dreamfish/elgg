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
?>

<div id="search-box">
    <?php
    $body = '<b>' . elgg_echo('groupsfrommembers:search') . '</b>';
    $body .= elgg_view('input/text', array('internalname' => 'name'));
    $body .= elgg_view('input/hidden', array('internalname' => 'entity', 'value' => $vars['entity']));
    $body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));

    echo elgg_view('input/form', array('body' => $body, 'action' => $vars['url'] . 'action/groupsfrommembers/search'));
    ?>
</div>