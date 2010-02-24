<?php
    if ($vars['entity']) {
        $period = $vars['entity']->period;
        if (!$period) $period = 'weekly';

        if (!is_plugin_enabled('invitefriends') || !$vars['entity']->invitecode) {
            $vars['entity']->invitecode = 'no';
        }

        $accesslist = $vars['entity']->accesslist;
        if (!$accesslist) {
	    $accesslist = "account/register.php\n"
	        . "account/forgotten_password.php\n"
	        . "action/siteaccess/code\n"
	        . "action/siteaccess/confirm\n"
	        . "action/register\n"
	        . "action/user/requestnewpassword\n"
	        . "pg/cron/reboot/\n"
	        . "pg/cron/minute/\n"
	        . "pg/cron/fiveminute/\n"
	        . "pg/cron/fifteenmin/\n"
	        . "pg/cron/halfhour/\n"
	        . "pg/cron/hourly/\n"
	        . "pg/cron/daily/\n"
	        . "pg/cron/weekly/\n"
	        . "pg/cron/monthly/\n"
	        . "pg/cron/yearly/\n"
	        . "pg/expages/read/About/\n"
	        . "pg/expages/read/Terms/\n"
	        . "pg/expages/read/Privacy/\n";
	    $vars['entity']->accesslist = $accesslist;
        } 

        if (!$vars['entity']->usesiteaccessemail) {
	    $vars['entity']->usesiteaccessemail = "yes";
        }

        if ($vars['entity']->usesiteaccessemail == "yes") {
            $vars['entity']->autoactivate = "no";
        }

        if (!$vars['entity']->autoactivate) {
	    $vars['entity']->autoactivate = "no";
        }
    
        if (!$vars['entity']->useriver) {
	    $vars['entity']->useriver = "yes";
        }

        if (!$vars['entity']->usesiteaccesscoppa) {
	    $vars['entity']->usesiteaccesscoppa = "no";
        }

        if (!$vars['entity']->usesiteaccesskey) {
	    $vars['entity']->usesiteaccesskey = "no";
        }

        if (!$vars['entity']->walledgarden) {
	    $vars['entity']->walledgarden = "no";
        }

        if ($vars['entity']->walledgarden == 'no') {
	    $vars['entity']->wg_debug = 'no';
        }
    
        if (!$vars['entity']->wg_debug) {
	    $vars['entity']->wg_debug = 'no';
        } 
    }
?>

<b><?php echo elgg_echo('siteaccess:reg:options'); ?></b>
<p>
    <?php echo elgg_echo('siteaccess:usesiteaccessemail'); ?> 
    <select name="params[usesiteaccessemail]">
        <option value="yes" <?php if ($vars['entity']->usesiteaccessemail != 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->usesiteaccessemail == 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select> 
</p>
<p>
    <?php echo elgg_echo('siteaccess:autoactivate'); ?>
    <select name="params[autoactivate]" <?php if ($vars['entity']->usesiteaccessemail == "yes") echo " disabled=\"disabled\""?>>
        <option value="yes" <?php if ($vars['entity']->autoactivate != 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->autoactivate == 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select>
</p>
<p>
    <?php echo elgg_echo('siteaccess:useriver'); ?>
    <select name="params[useriver]">
        <option value="yes" <?php if ($vars['entity']->useriver != 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->useriver == 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select>
</p>
<p>
    <?php echo elgg_echo('siteaccess:invitecode'); ?>
    <select name="params[invitecode]" <?php if (!is_plugin_enabled('invitefriends')) echo " disabled=\"disabled\""?>>
        <option value="yes" <?php if ($vars['entity']->invitecode == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->invitecode != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select>
    <?php echo elgg_echo('siteaccess:invitecode:info'); ?>
</p>
<p>
    <?php echo elgg_echo('siteaccess:usesiteaccesscoppa'); ?>
    <select name="params[usesiteaccesscoppa]">
        <option value="yes" <?php if ($vars['entity']->usesiteaccesscoppa == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->usesiteaccesscoppa != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select>
</p>
<p>
    <?php echo elgg_echo('siteaccess:usesiteaccesskey'); ?>
    <select name="params[usesiteaccesskey]">
	<option value="yes" <?php if ($vars['entity']->usesiteaccesskey == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->usesiteaccesskey != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select>
</p>
<p>
    <?php 
	echo elgg_echo('siteaccess:key:enter'); 
	echo elgg_view('input/text', array('internalname' => "params[siteaccesskey]", 'value' => $vars['entity']->siteaccesskey)); 
    ?>
</p>
<hr />
<b><?php echo elgg_echo('siteaccess:notify:options'); ?></b>
<p>
    <?php
        echo elgg_echo('siteaccess:notify');
	echo elgg_view('input/pulldown', array(
                        'internalname' => 'params[period]',
                        'options_values' => array(
                                'hourly' => elgg_echo('siteaccess:hourly'),
                                'daily' => elgg_echo('siteaccess:daily'),
                                'weekly' => elgg_echo('siteaccess:weekly'),
				'monthly' => elgg_echo('siteaccess:monthly'),
                        ),
                        'value' => $period
                ));
        echo elgg_view('input/text', array('internalname' => "params[notify]", 'value' => $vars['entity']->notify));
    ?>
</p>
<hr /> 
<b><?php echo elgg_echo('siteaccess:walledgarden:options'); ?></b>
<p>
    <?php echo elgg_echo('siteaccess:walledgarden'); ?>
    <select name="params[walledgarden]">
        <option value="yes" <?php if ($vars['entity']->walledgarden == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->walledgarden != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select>
</p>
<p>
    <?php echo elgg_echo('siteaccess:walledgarden:debug'); ?>
    <select name="params[wg_debug]"  <?php if ($vars['entity']->walledgarden == "no") echo " disabled=\"disabled\""?>>
        <option value="yes" <?php if ($vars['entity']->wg_debug == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
        <option value="no" <?php if ($vars['entity']->wg_debug != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
    </select>
</p>
<p>
    <?php echo elgg_echo('siteaccess:accesslist'); ?> 
    <?php echo elgg_view('siteaccess/input/longtext', array('internalname' => 'params[accesslist]', 'value' => $accesslist, 'disabled' => ($vars['entity']->walledgarden == "yes") ? false : true)); ?>
</p>



