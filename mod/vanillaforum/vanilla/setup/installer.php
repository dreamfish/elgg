<?php
// REPORT ALL ERRORS
error_reporting(E_ALL);
// DO NOT ALLOW PHP_SESS_ID TO BE PASSED IN THE QUERYSTRING
ini_set('session.use_only_cookies', 1);
// Track errors so explicit error messages can be reported should errors be encountered
ini_set('track_errors', 1);
// Define constant for magic_quotes
define('MAGIC_QUOTES_ON', get_magic_quotes_gpc());

// INCLUDE NECESSARY CLASSES & FUNCTIONS
include('../library/Framework/Framework.Functions.php');
include('../library/Framework/Framework.Class.Select.php');
include('../library/Framework/Framework.Class.SqlBuilder.php');
include('../library/Framework/Framework.Class.MessageCollector.php');
include('../library/Framework/Framework.Class.ErrorManager.php');
include('../library/Framework/Framework.Class.ConfigurationManager.php');

// Include database structure
include('../appg/database.php');

// Set application name and version
include('../appg/version.php');

// Set up some configuration defaults to override
$Configuration['DATABASE_HOST'] = '';
$Configuration['DATABASE_NAME'] = '';
$Configuration['DATABASE_USER'] = '';
$Configuration['DATABASE_PASSWORD'] = '';
$Configuration['DATABASE_CHARACTER_ENCODING'] = '';
$Configuration['APPLICATION_PATH'] = '';
$Configuration['DATABASE_PATH'] = '';
$Configuration['LIBRARY_PATH'] = '';
$Configuration['EXTENSIONS_PATH'] = '';
$Configuration['LANGUAGES_PATH'] = '';
$Configuration['THEME_PATH'] = '';
$Configuration['BASE_URL'] = '';
$Configuration['HTTP_METHOD'] = '';
$Configuration['DEFAULT_STYLE'] = '';
$Configuration['WEB_ROOT'] = '';
$Configuration['COOKIE_DOMAIN'] = '-';
$Configuration['COOKIE_PATH'] = '-';
$Configuration['SUPPORT_EMAIL'] = '';
$Configuration['SUPPORT_NAME'] = '';
$Configuration['FORWARD_VALIDATED_USER_URL'] = '';
$Configuration['CHARSET'] = 'utf-8';
$Configuration['DATABASE_TABLE_PREFIX'] = 'LUM_';
$Configuration['APPLICATION_TITLE'] = '';
$Configuration['BANNER_TITLE'] = '';
$Configuration['SETUP_COMPLETE'] = '0';
$Configuration['SETUP_TEST'] = '0';

class FauxContext {
	var $WarningCollector;
	var $ErrorManager;
	var $SqlCollector;
	var $Configuration;
	var $Dictionary;
	var $DatabaseTables;
	var $DatabaseColumns;
	var $Session;
	function GetDefinition($Code) {
		if (array_key_exists($Code, $this->Dictionary)) {
			return $this->Dictionary[$Code];
		} else {
			return $Code;
		}
	}
}

class FauxSession {
	var $User = false;
}

// Create warning & error handlers
$Context = new FauxContext();
$Context->Session = new FauxSession();
$Context->WarningCollector = new MessageCollector();
$Context->ErrorManager = new ErrorManager();
$Context->SqlCollector = new MessageCollector();
$Context->Configuration = $Configuration;
$Context->DatabaseTables = $DatabaseTables;
$Context->DatabaseColumns = $DatabaseColumns;
$Context->Dictionary = array();

// Dictionary Definitions
$Context->Dictionary['ErrReadFileSettings'] = 'An error occurred while attempting to read settings from the configuration file: ';
$Context->Dictionary['ErrOpenFile'] = 'The file could not be opened. Please make sure that PHP has write access to the //1 file.';
$Context->Dictionary['ErrWriteFile'] = 'The file could not be written.';

// Retrieve all postback parameters
$CurrentStep = ForceIncomingInt('Step', 0);
$PostBackAction = ForceIncomingString('PostBackAction', '');
$DBHost = ForceIncomingString('DBHost', '');
$DBName = ForceIncomingString('DBName', '');
$DBUser = ForceIncomingString('DBUser', '');
$DBPass = ForceIncomingString('DBPass', '');
$DBTablePrefix = ForceIncomingString('DBTablePrefix', $Configuration['DATABASE_TABLE_PREFIX']);
$Username = ForceIncomingString('Username', '');
$Password = ForceIncomingString('Password', '');
$ConfirmPassword = ForceIncomingString('ConfirmPassword', '');
$SupportEmail = ForceIncomingString('SupportEmail', '');
$SupportName = ForceIncomingString('SupportName', '');
$ApplicationTitle = ForceIncomingString('ApplicationTitle', 'Vanilla');
$CookieDomain = ForceIncomingString('CookieDomain', '');
$CookieDomain = FormatCookieDomain($CookieDomain);
$CookiePath = ForceIncomingString('CookiePath', '');

// Make the banner title the same as the application title
$WorkingDirectory = str_replace('\\', '/', getcwd()).'/';
$RootDirectory = str_replace('setup/', '', $WorkingDirectory);
$WebRoot = dirname(ForceString(@$_SERVER['PHP_SELF'], ''));
$WebRoot = substr($WebRoot, 0, strlen($WebRoot) - 5); // strips the "setup" off the end of the path.
$IsSecure = @$_SERVER["HTTPS"] || @$_SERVER['SERVER_PORT'] == 443;
$HttpMethod = $IsSecure ? 'https://' : 'http://';
$BaseUrl = $HttpMethod . ForceString(@$_SERVER['HTTP_HOST'], '').$WebRoot;
$ThemeDirectory = $WebRoot . 'themes/';
$AllowNext = 0;

function CreateFile($File, $Contents, &$Context) {
	if (!file_exists($File)) {
		$Handle = @fopen($File, 'wb');
		if (!$Handle) {
			$Error = $php_errormsg;
			if ($Error != '') $Error = 'The system reported the following message:<code>'.$Error.'</code>';
			$Context->WarningCollector->Add('Failed to create the \''.$File.'\' configuration file. '.$Error);
		} else {
			if (fwrite($Handle, $Contents) === FALSE) {
				$Context->WarningCollector->Add('Failed to write to the \''.$File.'\' file. Make sure that PHP has write access to the file.');
			}
			fclose($Handle);
		}
	}
}

// Step 1. Check for correct PHP, MySQL, and permissions
if ($PostBackAction == 'Permissions') {

	$FilePermissionIssues = 0;
	// Make sure we are running at least PHP 4.1.0
	if (intval(str_replace('.', '', phpversion())) < 410) $Context->WarningCollector->Add('It appears as though you are running PHP version '.phpversion().'. Vanilla requires at least version 4.1.0 of PHP. You will need to upgrade your version of PHP before you can continue.');
	// Make sure MySQL is available
	if (!function_exists('mysql_connect')) $Context->WarningCollector->Add('It appears as though you do not have MySQL enabled for PHP. You will need a working copy of MySQL and PHP&#8217;s MySQL extensions enabled in order to run Vanilla.');

	// Make sure the conf folder is writeable
	if (!is_writable('../conf/')) {
		$Context->WarningCollector->Add('Vanilla needs to have write permission enabled on the conf folder.');
		$FilePermissionIssues = 1;
	}

	// Make sure all folders are readable
	$TestFolder = array(
		'conf' => '../conf/',
		'extensions' => '../extensions/',
		'languages' => '../languages/',
		'themes' => '../themes/',
		'setup' => '../setup/'
	);
	foreach ($TestFolder as $FolderName => $Path) {
		if (!is_readable($Path)) {
			$Context->WarningCollector->Add('Vanilla needs to have read permission enabled on the '. $FolderName . ' folder.');
			$FilePermissionIssues = 1;
		}
	}

	// Make sure the files don't exist already (ie. the site is already up and running);
	if (file_exists('../conf/settings.php')) $Context->WarningCollector->Add('Vanilla seems to have been installed already. You will need to remove the conf/settings.php, conf/database.php files, and all database tables in order to run the installer utility again.');


	if ($Context->WarningCollector->Count() == 0) {
		$Contents = '<?php
// Database Configuration Settings
?>';
		CreateFile($RootDirectory.'conf/database.php', $Contents, $Context);
		$Contents = '<?php
// Make sure this file was not accessed directly and prevent register_globals configuration array attack
if (!defined(\'IN_VANILLA\')) exit();
// Enabled Extensions
?>';
		CreateFile($RootDirectory.'conf/extensions.php', $Contents, $Context);
		$Contents = '<?php
// Custom Language Definitions
?>';
		CreateFile($RootDirectory.'conf/language.php', $Contents, $Context);
		$Contents = '<?php
// Application Settings
?>';
		CreateFile($RootDirectory.'conf/settings.php', $Contents, $Context);
	}

	// Save a test configuration option to the conf/settings.php file (This is inconsequential and is only done to make sure we have read access).
	if ($Context->WarningCollector->Count() == 0) {
		$SettingsFile = $RootDirectory . 'conf/settings.php';
		$SettingsManager = new ConfigurationManager($Context);
		$SettingsManager->DefineSetting('SETUP_TEST', '1', 1);
		if (!$SettingsManager->SaveSettingsToFile($SettingsFile)) {
			$Context->WarningCollector->Clear();
			$Context->WarningCollector->Add('For some reason we couldn&#8217;t save your general settings to the \''.$SettingsFile.'\' file.');
		}
	}

	if ($Context->WarningCollector->Count() == 0) {
		// Redirect to the next step (this is done so that refreshes don't cause steps to be redone)
		Redirect($WebRoot.'setup/installer.php?Step=2&PostBackAction=None');
	}
} elseif ($PostBackAction == 'Database') {
	$CurrentStep = 2;

	// Make sure the database settings haven't already been committed
	$DBFile = $RootDirectory . 'conf/database.php';
	$DBManager = new ConfigurationManager($Context);
	$DBManager->GetSettingsFromFile($DBFile);
	// Make sure the database file doesn't already contain data
	if ($DBManager->GetSetting('DATABASE_NAME') != '') {
		$Context->WarningCollector->Add('Vanilla seems to have been installed already. You will need to remove the conf/settings.php, conf/database.php files, and all database tables in order to run the installer utility again.');
	} else {
		// Test the database params provided by the user
		$Connection = @mysql_connect($DBHost, $DBUser, $DBPass);
		$DatabaseCharacterEncoding = '';
		if (!$Connection) {
			$Response = '';
			if ($php_errormsg != '') $Response = ' The database responded with the following message: '.$php_errormsg;
			$Context->WarningCollector->Add('We couldn&#8217;t connect to the server you provided ('.$DBHost.').'.$Response);
		} elseif (!mysql_select_db($DBName, $Connection)) {
			$Context->WarningCollector->Add('We connected to the server, but we couldn&#8217;t access the \''.$DBName.'\' database. Are you sure it exists and that the specified user has access to it?');
		}

		// If the database connection worked, attempt to set up the database
		if ($Context->WarningCollector->Count() == 0 && $Connection) {
			// On MySQL 4.1 and later, force UTF-8
			if (version_compare(mysql_get_server_info($Connection), '4.1.0', '>=')) {
				mysql_query('SET NAMES "utf8"', $Connection);
				$DatabaseCharacterEncoding = 'utf8';
			}

			// Make sure there are no conflicting tables in the database
			$TableData = @mysql_query('show tables', $Connection);
			if (!$TableData) {
				$Context->WarningCollector->Add('We had some problems identifying the tables already in your database: '. mysql_error($Connection));
			} else {
				$TableConflicts = array();
				$TableToCompare = array();
				while ($Row = mysql_fetch_array($TableData)) {
					$TableToCompare[] = strtolower($Row[0]);
				}
				foreach ($DatabaseTables as $TableKey => $TableName) {
					$TableName = GetTableName($TableKey, $DatabaseTables, $DBTablePrefix);
					$TableName = strtolower(iif($TableKey == 'User', $DBTablePrefix, '') . $TableName);
					if (in_array($TableName, $TableToCompare)) {
						$TableConflicts[] = $TableName;
					}
				}
				if (count($TableConflicts) == count($DatabaseTables)) {
					$Context->WarningCollector->Add('It appears as though you&#8217;ve already got Vanilla installed. If you are attempting to upgrade your existing installation of Vanilla, you should be using the <a href="upgrader.php">upgrade script</a>.');
					$AllowNext = 1;
				} elseif (count($TableConflicts) > 0) {
					$Context->WarningCollector->Add('There appear to be some tables already in your database that conflict with the tables Vanilla would need to insert. Those tables are: <code>'.implode(', ', $TableConflicts).'</code>If you are attempting to upgrade your existing installation of Vanilla, you should be using the <a href="upgrader.php">upgrade script</a>.');
				} else {
					// Go ahead and install the database tables
					// Open the database file & retrieve sql
					$SqlLines = @file($WorkingDirectory.'mysql.sql');

					if (!$SqlLines) {
						$Context->WarningCollector->Add('We couldn&#8217;t open the \''.$WorkingDirectory.'mysql.sql\' file.');
					} else {
						$CurrentQuery = '';
						$CurrentLine = '';
						for ($i = 0; $i < count($SqlLines); $i++) {
							$CurrentLine = trim($SqlLines[$i]);
							if ($CurrentLine == '') {
								if ($CurrentQuery != '') {
									// If the current query creates a table, make sure that the proper character encoding is applied.
									if ($DatabaseCharacterEncoding == 'utf8' && strpos($CurrentQuery, 'REATE TABLE') == 1) {
										str_replace(');', ') DEFAULT CHARACTER SET utf8;', $CurrentQuery);
									}
									$CurrentQuery = preg_replace('/((TABLE|INTO) `)LUM_/', '${1}' . $DBTablePrefix, $CurrentQuery);
									if (!@mysql_query($CurrentQuery, $Connection)) {
										$Context->WarningCollector->Add('An error occurred while we were attempting to create the database tables. MySQL reported the following error: <code>'.mysql_error($Connection).'</code><code>QUERY: '.$CurrentQuery.'</code>');
										$i = count($SqlLines)+1;
									}
									$CurrentQuery = '';
								}
							} else {
								$CurrentQuery .= $CurrentLine;
							}
						}
					}
				}
			}
			// Close the database connection
			@mysql_close($Connection);
		}

		// If the database was created successfully, save all parameters to the conf/database.php file
		if ($Context->WarningCollector->Count() == 0) {
			$DBManager->DefineSetting('DATABASE_HOST', $DBHost, 1);
			$DBManager->DefineSetting('DATABASE_NAME', $DBName, 1);
			$DBManager->DefineSetting('DATABASE_USER', $DBUser, 1);
			$DBManager->DefineSetting('DATABASE_PASSWORD', $DBPass, 1);
			$DBManager->DefineSetting('DATABASE_TABLE_PREFIX', $DBTablePrefix, 1);
			$DBManager->DefineSetting('DATABASE_CHARACTER_ENCODING', $DatabaseCharacterEncoding, 1);
			if (!$DBManager->SaveSettingsToFile($DBFile)) {
				// $Context->WarningCollector->Clear();
				// $Context->WarningCollector->Add('For some reason we couldn&#8217;t save your database settings to the '.$DBFile.' file.');
			}

			// Save user table name
			if (!AppendToConfigurationFile($RootDirectory.'conf/database.php',
					'$DatabaseTables[\'User\'] = \''.$DBTablePrefix."User';\n")) {
				// $Context->WarningCollector->Clear();
				// $Context->WarningCollector->Add('For some reason we couldn&#8217;t save your database settings to the '.$DBFile.' file.');
			}

			// Save the general settings as well (now that we know this person is authenticated to
			// a degree - knowing the database access params).
			$SettingsFile = $RootDirectory . 'conf/settings.php';
			$SettingsManager = new ConfigurationManager($Context);
			$SettingsManager->DefineSetting('APPLICATION_PATH', $RootDirectory, 1);
			$SettingsManager->DefineSetting('DATABASE_PATH', $RootDirectory . 'conf/database.php', 1);
			$SettingsManager->DefineSetting('LIBRARY_PATH', $RootDirectory . 'library/', 1);
			$SettingsManager->DefineSetting('EXTENSIONS_PATH', $RootDirectory . 'extensions/', 1);
			$SettingsManager->DefineSetting('LANGUAGES_PATH', $RootDirectory . 'languages/', 1);
			$SettingsManager->DefineSetting('THEME_PATH', $RootDirectory . 'themes/vanilla/', 1);
			$SettingsManager->DefineSetting('DEFAULT_STYLE', $ThemeDirectory.'vanilla/styles/default/', 1);
			$SettingsManager->DefineSetting('WEB_ROOT', $WebRoot, 1);
			$SettingsManager->DefineSetting('BASE_URL', $BaseUrl, 1);
			$SettingsManager->DefineSetting('HTTP_METHOD', $HttpMethod, 1);
			$SettingsManager->DefineSetting('FORWARD_VALIDATED_USER_URL', $BaseUrl, 1);
			if (!$SettingsManager->SaveSettingsToFile($SettingsFile)) {
				// $Context->WarningCollector->Clear();
				// $Context->WarningCollector->Add('For some reason we couldn&#8217;t save your general settings to the '.$SettingsFile.' file.');
			}
		}
	}

	if ($Context->WarningCollector->Count() == 0) {
		If ($DBPass == '') {
			$Context->WarningCollector->Add('Your configuration contains a blank MySQL password, please note that this is a potential security risk, and not supported by Vanilla. Make a note that before you will be able to sign in, you will need to add the following line to conf/settings.php: <code>$Configuration[\'DATABASE_PASSWORD\'] = \'\';</code><p /><a href="'.$WebRoot.'setup/installer.php?Step=3&PostBackAction=None">Continue installation</a> with these settings, or specify a different account.');
		} else {
			// Redirect to the next step (this is done so that refreshes don't cause steps to be redone)
			Redirect($WebRoot.'setup/installer.php?Step=3&PostBackAction=None');
		}
	}
} elseif ($PostBackAction == 'User') {
	$CurrentStep = 3;

	$SettingsFile = $RootDirectory . 'conf/settings.php';
	$SettingsManager = new ConfigurationManager($Context);
	$SettingsManager->GetSettingsFromFile($SettingsFile);
	if ($SettingsManager->GetSetting('SETUP_COMPLETE') != '0') {
		$Context->WarningCollector->Add('Vanilla seems to have been installed already. You will need to remove the conf/settings.php, conf/database.php files, and all database tables in order to run the installer utility again.');
	} else {

		// Validate user inputs
		if (strip_tags($Username) != $Username) $Context->WarningCollector->Add('You really shouldn&#8217;t have any html into your username.');
		if (strlen($Username) > 20) $Context->WarningCollector->Add('Your username is too long');
		if ($Password != $ConfirmPassword) $Context->WarningCollector->Add('The passwords you entered didn&#8217;t match.');
		if (strip_tags($ApplicationTitle) != $ApplicationTitle) $Context->WarningCollector->Add('You can&#8217;t have any html in your forum name.');
		if ($Username == '') $Context->WarningCollector->Add('You must provide a username.');
		if ($Password == '') $Context->WarningCollector->Add('You must provide a password.');
		if ($SupportName == '') $Context->WarningCollector->Add('You must provide a support contact name.');
		if (!eregi('(.+)@(.+)\.(.+)', $SupportEmail)) $Context->WarningCollector->Add('The email address you entered doesn&#8217;t appear to be valid.');
		if ($ApplicationTitle == '') $Context->WarningCollector->Add('You must provide an application title.');

		// Include the db settings defined in the previous step
		include($RootDirectory.'conf/database.php');
		$Context->Configuration = $Configuration;
		$Context->DatabaseTables = $DatabaseTables;

		// Open the database connection
		$Connection = false;
		if ($Context->WarningCollector->Count() == 0) {
			$DBHost = $Configuration['DATABASE_HOST'];
			$DBName = $Configuration['DATABASE_NAME'];
			$DBUser = $Configuration['DATABASE_USER'];
			$DBPass = $Configuration['DATABASE_PASSWORD'];
			$Connection = @mysql_connect($DBHost, $DBUser, $DBPass);
			if (!$Connection) {
				$Context->WarningCollector->Add('We couldn&#8217;t connect to the server you provided ('.$DBHost.'). Are you sure you entered the right server, username and password?');
			} elseif (!mysql_select_db($DBName, $Connection)) {
				$Context->WarningCollector->Add('We connected to the server, but we couldn&#8217;t access the \''.$DBName.'\' database. Are you sure it exists and that the specified user has access to it?');
			} elseif (version_compare(mysql_get_server_info($Connection), '4.1.0', '>=')) {
				/* On MySQL 4.1 and later, force UTF-8 */
				mysql_query('SET NAMES "utf8"', $Connection);
			}
		}

		// Create the administrative user
		if ($Context->WarningCollector->Count() == 0 && $Connection) {
			$Username = FormatStringForDatabaseInput($Username);
			$Password = FormatStringForDatabaseInput($Password);

			$s = new SqlBuilder($Context);
			$s->SetMainTable('User', 'u');
			$s->AddFieldNameValue('FirstName', 'Administrative');
			$s->AddFieldNameValue('LastName', 'User');
			$s->AddFieldNameValue('Email', FormatStringForDatabaseInput($SupportEmail));
			$s->AddFieldNameValue('Name', $Username);
			$s->AddFieldNameValue('Password', $Password, 1, 'md5');
			$s->AddFieldNameValue('DateFirstVisit', MysqlDateTime());
			$s->AddFieldNameValue('DateLastActive', MysqlDateTime());
			$s->AddFieldNameValue('CountVisit', 0);
			$s->AddFieldNameValue('CountDiscussions', 0);
			$s->AddFieldNameValue('CountComments', 0);
			$s->AddFieldNameValue('RoleID', 4);
			$s->AddFieldNameValue('StyleID', 1);
			$s->AddFieldNameValue('UtilizeEmail', 0);
			$s->AddFieldNameValue('RemoteIp', GetRemoteIp(1));
			if (!@mysql_query($s->GetInsert(), $Connection)) {
				$Context->WarningCollector->Add('Something bad happened when we were trying to create your administrative user account. Mysql said: '.mysql_error($Connection));
			} else {
				// Now insert the role history assignment
				$NewUserID = mysql_insert_id($Connection);
				$s->Clear();
				$s->SetMainTable('UserRoleHistory', 'h');
				$s->AddFieldNameValue('UserID', $NewUserID);
				$s->AddFieldNameValue('RoleID', 4);
				$s->AddFieldNameValue('Date', MysqlDateTime());
				$s->AddFieldNameValue('AdminUserID', $NewUserID);
				$s->AddFieldNameValue('Notes', 'Initial administrative account created');
				$s->AddFieldNameValue('RemoteIp', GetRemoteIp(1));
				// Fail silently on this one
				@mysql_query($s->GetInsert(), $Connection);
			}
			// Create the default Vanilla style entry in the db
			$s->Clear();
			$s->SetMainTable('Style', 's');
			$s->AddFieldNameValue('Name', 'Vanilla');
			$s->AddFieldNameValue('Url', $ThemeDirectory.'vanilla/styles/default/');
			@mysql_query($s->GetInsert(), $Connection);
		}

		// Close the database connection
		@mysql_close($Connection);

		// Save the application constants
		if ($Context->WarningCollector->Count() == 0) {
			$SettingsManager->DefineSetting('SUPPORT_EMAIL', $SupportEmail, 1);
			$SettingsManager->DefineSetting('SUPPORT_NAME', $SupportName, 1);
			$SettingsManager->DefineSetting('APPLICATION_TITLE', $ApplicationTitle, 1);
			$SettingsManager->DefineSetting('BANNER_TITLE', $ApplicationTitle, 1);
			$SettingsManager->DefineSetting('COOKIE_DOMAIN', $CookieDomain, 1);
			$SettingsManager->DefineSetting('COOKIE_PATH', $CookiePath, 1);
			$SettingsManager->DefineSetting('SETUP_COMPLETE', '1', 1);
			$SettingsManager->SaveSettingsToFile($SettingsFile);
		}
	}

	if ($Context->WarningCollector->Count() == 0) {
		// Redirect to the next step (this is done so that refreshes don't cause steps to be redone)
		Redirect($WebRoot.'setup/installer.php?Step=4&PostBackAction=None');
	}
}

// Write the page
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
	<head>
		<title><?php echo APPLICATION . ' ' . APPLICATION_VERSION; ?> Installer</title>
		<link rel="stylesheet" type="text/css" href="./style.css" />
	</head>
	<body>
		<h1>
			<span><strong><?php echo APPLICATION . ' ' . APPLICATION_VERSION; ?></strong> Installer</span>
		</h1>
		<div class="Container">
			<div class="Content">
			<?php
			if ($CurrentStep < 2 || $CurrentStep > 4) {
				echo  '<h2>Vanilla Installation Wizard (Step 1 of 3)</h2>';
				if ($Context->WarningCollector->Count() > 0) {
					// display error message and some help messages.
					echo '<div class="Warnings">
						<strong>We came across some problems while checking your permissions...</strong>
						'.$Context->WarningCollector->GetMessages().'
					</div>';

					if ($FilePermissionIssues) {
						echo '<p>Navigate the filesystem of your server to the Vanilla folder.
						Vanilla will need read AND write access to the <strong>conf</strong> folder.</p>

						<p>There are many ways to set these permissions.
						One way is to use your shell access
						and to execute the following from the root Vanilla folder:</p>

						<code>chmod 777 ./conf</code>

						<p>You will also need to grant read access to the extensions, languages, setup,
						and themes folders. Typically these permissions are granted by default,
						but if not you can achieve them with the following commands:</p>

							<code>chmod -R 755 ./extensions
							<br />chmod -R 755 ./languages
							<br />chmod -R 755 ./setup
							<br />chmod -R 755 ./themes</code>
						';
					}
				} else {
					echo '<p>This wizard will first check can been installed.</p>';
				}


				echo '<form id="frmPermissions" method="post" action="installer.php">
				<input type="hidden" name="PostBackAction" value="Permissions" />
				<div class="Button"><input type="submit" value="Click here to check your installation and proceed to the next step" /></div>
				</form>';
			} elseif ($CurrentStep == 2) {
					echo '<h2>Vanilla Installation Wizard (Step 2 of 3)</h2>';
					if ($Context->WarningCollector->Count() > 0) {
						echo '<div class="Warnings">
							<strong>We came across some problems while setting up Vanilla...</strong>
							'.$Context->WarningCollector->GetMessages().'
						</div>';
					}
					echo '<p>Create your new Vanilla database, and specify the MySQL connection parameters below:</p>
					<fieldset>
						<form id="frmDatabase" method="post" action="installer.php">
						<input type="hidden" name="PostBackAction" value="Database" />
							<ul>
								<li>
									<label for="tDBHost">MySQL Server</label>
									<input type="text" id="tDBHost" name="DBHost" value="'.FormatStringForDisplay($DBHost, 1).'" />
								</li>
								<li>
									<label for="tDBName">MySQL Database Name</label>
									<input type="text" id="tDBName" name="DBName" value="'.FormatStringForDisplay($DBName, 1).'" />
								</li>
								<li>
									<label for="tDBUser">MySQL User</label>
									<input type="text" id="tDBUser" name="DBUser" value="'.FormatStringForDisplay($DBUser, 1).'" />
								</li>
								<li>
									<label for="tDBPass">MySQL Password</label>
									<input type="password" id="tDBPass" name="DBPass" value="'.FormatStringForDisplay($DBPass, 1).'" />
								</li>
								<li>
									<label for="tDBTablePrefix">MySQL Table Prefix</label>
									<input type="text" id="tDBTablePrefix" name="DBTablePrefix" value="'.FormatStringForDisplay($DBTablePrefix, 1).'" />
								</li>
							</ul>
							<div class="Button"><input type="submit" value="Click here to create Vanilla&#8217;s database and proceed to the next step" /></div>
						</form>
					</fieldset>';
				} elseif ($CurrentStep == 3) {
					if ($PostBackAction != 'User') {
						$CookieDomain = ForceString(@$_SERVER['HTTP_HOST'], '');
						$CookieDomain = FormatCookieDomain($CookieDomain);
						$CookiePath = $WebRoot;
					}
					echo '<h2>Vanilla Installation Wizard (Step 3 of 3)</h2>';
					if ($Context->WarningCollector->Count() > 0) {
						echo '<div class="Warnings">
							<strong>We came across some problems while setting up Vanilla...</strong>
							'.$Context->WarningCollector->GetMessages().'
						</div>';
					}
					echo '<p>Now let&#8217;s set up your administrative account for Vanilla.</p>
						<fieldset>'
						.'<form name="frmUser" method="post" action="installer.php">
						<input type="hidden" name="PostBackAction" value="User" />
						<ul>
							<li>
								<label for="tUsername">Username</label>
								<input id="tUsername" type="text" name="Username" value="'.FormatStringForDisplay($Username, 1).'" />
							</li>
							<li>
								<label for="tPassword">Password</label>
								<input id="tPassword" type="password" name="Password" value="'.FormatStringForDisplay($Password, 1).'" />
							</li>
							<li>
								<label for="tConfirmPassword">Confirm Password</label>
								<input id="tConfirmPassword" type="password" name="ConfirmPassword" value="'.FormatStringForDisplay($ConfirmPassword, 1).'" />
							</li>
						</ul><p>Up next we&#8217;ve got to set up the support contact information for your forum. This is what people will see when emails go out from the system for things like password retrieval and role changes.</p>
						<ul>
							<li>
								<label for="tSupportName">Support Contact Name</label>
								<input id="tSupportName" type="text" name="SupportName" value="'.FormatStringForDisplay($SupportName, 1).'" />
							</li>
							<li>
								<label for="tSupportEmail">Support Email Address</label>
								<input id="tSupportEmail" type="text" name="SupportEmail" value="'.FormatStringForDisplay($SupportEmail, 1).'" />
							</li>
						</ul>
						<p>What do you want to call your forum?</p>
						<ul>
							<li>
								<label for="tApplicationTitle">Forum Name</label>
								<input id="tApplicationTitle" type="text" name="ApplicationTitle" value="'.FormatStringForDisplay($ApplicationTitle, 1).'" />
							</li>
						</ul>
						<p>The cookie domain is where you want cookies assigned to for Vanilla. Typically the cookie domain will be something like www.yourdomain.com. Cookies can be further defined to a particular path on your website using the "Cookie Path" setting. (TIP: If you want your Vanilla cookies to apply to all subdomains of your domain, use ".yourdomain.com" as the cookie domain).</p>
						<ul>
							<li>
								<label for="tCookieDomain">Cookie Domain</label>
								<input id="tCookieDomain" type="text" name="CookieDomain" value="'.FormatStringForDisplay($CookieDomain, 1).'" />
							</li>
							<li>
								<label for="tCookiePath">Cookie Path</label>
								<input id="tCookiePath" type="text" name="CookiePath" value="'.FormatStringForDisplay($CookiePath, 1).'" />
							</li>
						</ul>
						<div class="Button"><input type="submit" value="Click here to complete the setup process!" /></div>
						</form>
					</fieldset>';
				} else {
					echo '<h2>Vanilla Installation Wizard (Complete)</h2>
					<p><strong>That&#8217;s it! Vanilla is set up and ready to go.</strong></p>
					<p>Before you start inviting your friends in for discussions, there are a lot of other things you might want to set up. The #1 thing on your list should be getting some add-ons for Vanilla. With add-ons you can do all sorts of cool things like:</p>
					<ul>
						<li>Allow your users to save their common searches</li>
						<li>Change the language, layout, and style of your forum</li>
						<li>Add Atom and RSS feeds to your forum</li>
						<li>Allow users to quote other users in discussions</li>
					</ul>
					<p>All of these extensions (and a lot more) are available in <a href="http://lussumo.com/addons/" target="Lussumo">the Vanilla Add-on directory</a>.</p>
					<p>You&#8217;ll also want to fine-tune your application settings, like:</p>
					<ul>
						<li>Change the number of discussions or comments per page</li>
						<li>Allow the public to browse your forum without an account</li>
						<li>Create new roles with various different permissions</li>
						<li>Create new categories, and even limit which roles get to access them</li>
					</ul>
					<p>All of these configuration options (and many more) are available in the settings tab of your Vanilla forum.</p>

					<p>If you need some help getting started with administering your new Vanilla forum, you can <a href="http://lussumo.com/docs" target="Lussumo">read the complete documentation</a> or ask for help on the <a href="http://lussumo.com/community/" target="Lussumo">Lussumo Community Forum</a>. Enough talking...</p>
					<div class="Button"><a href="../people.php">Go sign in and have some fun!</a></div>';
				}
				?>
			</div>
		</div>
	</body>
</html>
