<?php
/*
* Copyright 2003 Mark O'Sullivan
* This file is part of People.
* People is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* People is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with People; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for People is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*
* Description: Constants and objects specific to external to the forum (ie. sign in, apply, password retrieval).
*/
// Make sure this file was not accessed directly and prevent register_globals configuration array attack
if (!defined('IN_VANILLA')) exit();

// GLOBAL INCLUDES
include_once($Configuration['APPLICATION_PATH'].'appg/headers.php');
include_once($Configuration['APPLICATION_PATH'].'appg/database.php');
include_once($Configuration['DATABASE_PATH']);
include_once($Configuration['LIBRARY_PATH'].'Framework/Framework.Functions.php');
include_once($Configuration['LIBRARY_PATH'].'Framework/Framework.Class.Database.php');
include_once($Configuration['LIBRARY_PATH'].'Framework/Framework.Class.'.$Configuration['DATABASE_SERVER'].'.php');
include_once($Configuration['LIBRARY_PATH'].'Framework/Framework.Class.SqlBuilder.php');
include_once($Configuration['LIBRARY_PATH'].'Framework/Framework.Class.MessageCollector.php');
include_once($Configuration['LIBRARY_PATH'].'Framework/Framework.Class.ErrorManager.php');
include_once($Configuration['LIBRARY_PATH'].'Framework/Framework.Class.ObjectFactory.php');
include_once($Configuration['LIBRARY_PATH'].'Framework/Framework.Class.StringManipulator.php');
include_once($Configuration['LIBRARY_PATH'].'Framework/Framework.Class.Context.php');
include_once($Configuration['LIBRARY_PATH'].'Framework/Framework.Class.Delegation.php');
include_once($Configuration['LIBRARY_PATH'].'Framework/Framework.Class.Control.php');
include_once($Configuration['LIBRARY_PATH'].$Configuration['AUTHENTICATION_MODULE']);
include_once($Configuration['LIBRARY_PATH'].'People/People.Class.Session.php');
include_once($Configuration['LIBRARY_PATH'].'People/People.Class.PasswordHash.php');
include_once($Configuration['LIBRARY_PATH'].'People/People.Class.User.php');

// INSTANTIATE THE CONTEXT OBJECT
// The context object handles the following:
// - Open a connection to the database
// - Create a user session (autologging in any user with valid cookie credentials)
// - Instantiate debug and warning collectors
// - Instantiate an error manager
// - Define global variables relative to the current context (SelfUrl
$Context = new Context($Configuration);
$Context->DatabaseTables = &$DatabaseTables;
$Context->DatabaseColumns = &$DatabaseColumns;

// Start the session management
$Context->StartSession();

// DEFINE THE LANGUAGE DICTIONARY
include($Configuration['LANGUAGES_PATH'].$Configuration['LANGUAGE'].'/definitions.php');
include($Configuration['APPLICATION_PATH'].'conf/language.php');

// INSTANTIATE THE PAGE OBJECT
// The page object handles collecting all page controls
// and writing them when it's events are fired.
$Page = $Context->ObjectFactory->NewContextObject($Context, 'Page', $Configuration['PAGE_EVENTS']);

// FIRE INITIALIZATION EVENT
$Page->FireEvent('Page_Init');

// DEFINE THE MASTER PAGE CONTROLS
$Head = $Context->ObjectFactory->CreateControl($Context, 'Head');
$Banner = $Context->ObjectFactory->CreateControl($Context, 'Filler', 'people_banner.php');
$Foot = $Context->ObjectFactory->CreateControl($Context, 'PeopleFoot');
$PageEnd = $Context->ObjectFactory->CreateControl($Context, 'PageEnd');

// BUILD THE PAGE HEAD
// Every page will require some basic definitions for the header.
$Head->AddScript('js/global.js');
$Head->AddStyleSheet($Context->StyleUrl.'people.css', 'screen', 100, '');

// INCLUDE EXTENSIONS
if ($Configuration['PEOPLE_USE_EXTENSIONS']) {
	include($Configuration['APPLICATION_PATH'].'conf/extensions.php');

	// Make sure to get all delegates from the extensions into objects which were
	// constructed before the extensions were loaded.
	$Head->GetDelegatesFromContext();
	$Banner->GetDelegatesFromContext();
	$Foot->GetDelegatesFromContext();
	$PageEnd->GetDelegatesFromContext();
}
?>