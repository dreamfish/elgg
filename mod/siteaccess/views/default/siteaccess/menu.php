<?php

    $show = $vars['show'];
    $url = $vars['url'] . 'pg/siteaccess/';
    $count = $vars['count'];
    if (!$count)
        $count = 0;
?>

<div id="elgg_horizontal_tabbed_nav">
<ul>
    <li <?php if($show == 'activate') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>activate"><?php echo elgg_echo('siteaccess:list:activate'); ?></a></li>
    <li <?php if($show == 'banned') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>banned"><?php echo elgg_echo('siteaccess:list:banned'); ?></a></li>
    <li <?php if($show == 'validate') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>validate"><?php echo elgg_echo('siteaccess:list:validate'); ?></a></li>
    <li <?php if($show == 'templates') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>templates"><?php echo elgg_echo('siteaccess:list:templates'); ?></a></li>
</ul>
</div>

<div class="group_count"> 
    <?php if ($show != 'templates') echo $count . " " . elgg_echo('siteaccess:found'); ?>
</div>
