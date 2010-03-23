<?php
// Set application name and version
include('../appg/version.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
	<head>
		<title><?php echo APPLICATION . ' ' . APPLICATION_VERSION; ?></title>
		<link rel="stylesheet" type="text/css" href="./style.css" />
	</head>
	<body>
		<h1>
			<span><strong><?php echo APPLICATION . ' ' . APPLICATION_VERSION; ?></strong> Setup</span>
		</h1>
		<div class="Container">
			<div class="Content">
				<h2>Installing Fresh</h2>
				<p>If you are creating a brand new installation of Vanilla, all you need to do is upload all of the Vanilla files to your server. Once you are finished uploading, open this file in your web browser and click the install link below.</p>
				<div class="Button"><a href="installer.php">Click here to install a completely brand new version of Vanilla</a></div>

				<h2>Upgrading</h2>

				<div style="margin-top: 16px; padding: 8px; border: 1px solid #D53F90; background: #FFE6F4; color: #A52068; font-weight: bold;">If you are upgrading from Vanilla 1.x, <a style="color: #A52068;" href="http://lussumo.com/upgrade/">read the upgrade instructions online</a>.</div>

				<p><strong>If you are upgrading from Vanilla 0.9.2.x, here are a few things you should do first:</strong></p>
				<ul>
					<li>
						<strong>Back up your Database</strong>
						<p>The upgrader will be performing structural changes on your database, you should have a backup of your old database just to be safe.</p>
					</li>
					<li>
						<strong>Back up your old Vanilla files</strong>
						<p>Download and save your old Vanilla files to your local machine. Specifically, we can use your old appg/settings.php file for importing old settings.</p>
					</li>
				</ul>

				<p>When you are finished backing everything up, upload all of the new Vanilla files to your server, open this file in your web browser and click the upgrade link below.</p>

				<div class="Button"><a href="upgrader.php">Click here to upgrade from Vanilla 0.9.2.x to <?php echo APPLICATION . ' ' . APPLICATION_VERSION; ?></a></div>
			</div>
		</div>
	</body>
</html>