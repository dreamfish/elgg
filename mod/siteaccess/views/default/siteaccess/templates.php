<?php
    echo '<p><b>' . elgg_echo('siteaccess:email:valid:macros') . '</b><br />'; 
    echo '<table class="siteaccess_macros"><tr><td>%site_name%</td><td>' .  siteaccess_parser($_SESSION['user'], '%site_name%') . '</td></tr>';
    echo '<tr><td>%site_url%</td><td>' . siteaccess_parser($_SESSION['user'], '%site_url%')  . '</td></tr>';
    echo '<tr><td>%username%</td><td>' . siteaccess_parser($_SESSION['user'], '%username%') . '</td></tr>';
    echo '<tr><td>%name%</td><td>' . siteaccess_parser($_SESSION['user'], '%name%') . '</td></tr>';
    echo '<tr><td>%confirm_url%</td><td>' . siteaccess_parser($_SESSION['user'], '%confirm_url%') . '</td></tr>'; 
    echo '<tr><td>%admin_url%</td><td>' . siteaccess_parser($_SESSION['user'], '%admin_url%') . '</td></tr>'; 
    echo '</table></p>';
?>
<hr />
<div class="email_details"> 
<p><a class="collapsibleboxlink">[<?php echo elgg_echo('siteaccess:email:label:adminactivated'); ?>]</a></p>
<div class="collapsible_box">
    <?php 
        $email = siteaccess_get_email('admin_activated');
        $form_body = elgg_view('siteaccess/email', array('email' => $email)); 
        echo elgg_view('input/form', array('action' => "{$vars['url']}action/siteaccess/email/save", 'body' => $form_body));
    ?>    
</div>
</div>

<div class="email_details">
<p><a class="collapsibleboxlink">[<?php echo elgg_echo('siteaccess:email:label:confirmed'); ?>]</a></p>
<div class="collapsible_box">
    <?php
        $email = siteaccess_get_email('confirm');
        $form_body = elgg_view('siteaccess/email', array('email' => $email));
        echo elgg_view('input/form', array('action' => "{$vars['url']}action/siteaccess/email/save", 'body' => $form_body));
    ?>
</div>
</div>

<div class="email_details">
<p><a class="collapsibleboxlink">[<?php echo elgg_echo('siteaccess:email:label:validated'); ?>]</a></p>
<div class="collapsible_box">
    <?php
        $email = siteaccess_get_email('validated');
        $form_body = elgg_view('siteaccess/email', array('email' => $email));
        echo elgg_view('input/form', array('action' => "{$vars['url']}action/siteaccess/email/save", 'body' => $form_body));
    ?>
</div>
</div>

<div class="email_details">
<p><a class="collapsibleboxlink">[<?php echo elgg_echo('siteaccess:email:label:notifyadmin'); ?>]</a></p>
<div class="collapsible_box">
    <?php
        $email = siteaccess_get_email('notify_admin');
        $form_body = elgg_view('siteaccess/email', array('email' => $email));
        echo elgg_view('input/form', array('action' => "{$vars['url']}action/siteaccess/email/save", 'body' => $form_body));
    ?>
</div>
</div>
