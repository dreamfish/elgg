<?php
/**
 * Class that handles user sessions.
 *
 * Copyright 2003 Mark O'Sullivan
 * This file is part of Lussumo's Software Library.
 * Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * The latest source code is available at www.lussumo.com
 * Contact Mark O'Sullivan at mark [at] lussumo [dot] com
 *
 * @author Mark O'Sullivan
 * @copyright 2003 Mark O'Sullivan
 * @license http://lussumo.com/community/gpl.txt GPL 2
 * @package People
 * @version 1.1.7
 */


/**
 * Handles user sessions.
 * @package People
 */

class PeopleSession {
	/**
	 * Unique user identifier
	 * @var int
	 */
	var $UserID;

	/**
	 * User object containing properties relevant to session
	 * @var User
	 */
	var $User;

	/**
	 * Ensure that there is an active session.
	 *
	 * If there isn't an active session, send the user to the SignIn Url
	 *
	 * @param Context $Context
	 */
	function Check(&$Context) {
		if (($this->UserID == 0 && !$Context->Configuration['PUBLIC_BROWSING']) || ($this->UserID > 0 && !$this->User->PERMISSION_SIGN_IN)) {
			if ($this->UserID > 0 && !$this->User->PERMISSION_SIGN_IN) $this->End($Context->Authenticator);
			$Url = AppendUrlParameters(
				$Context->Configuration['SAFE_REDIRECT'],
				'ReturnUrl=' . urlencode( GetRequestUri() ) );
			Redirect($Url);
		}
	}

	/**
	 * End the session and remove the session data.
	 */
	function Destroy() {
		if (session_id()) {
			session_destroy();
		}
		$this->UserID = 0;
		if($this->User) {
			$this->User->Clear();
		}
	}


	/**
	 * End a session
	 *
	 * @param Authenticator $Authenticator
	 */
	function End($Authenticator) {
		$Authenticator->DeAuthenticate();
	}

	/**
	 * Get a session variable
	 *
	 * @param string $Name
	 * @param string $DataType Can be int|bool|array|string.
	 * @return int|boolean|array|string
	 */
	function GetVariable($Name, $DataType = 'bool') {
		if ($DataType == 'int') {
			return ForceInt(@$_SESSION[$Name], 0);
		} else if ($DataType == 'bool') {
			return ForceBool(@$_SESSION[$Name], 0);
		} else if ($DataType == 'array') {
			return ForceArray(@$_SESSION[$Name], array());
		} else {
			return ForceString(@$_SESSION[$Name], '');
		}
	}

	/**
	 * Set a session variable
	 *
	 * @param string $Name
	 * @param int|bool|array|string $Value
	 */
	function SetVariable($Name, $Value) {
		@$_SESSION[$Name] = $Value;
	}

	/**
	 * Return the key used for CSRF protection.
	 * @return String
	 */
	function GetCsrfValidationKey() {
		$Key = $this->GetVariable('SessionPostBackKey', 'string');
		if ($Key == '') {
			$Key = DefineVerificationKey();
			$this->SetVariable('SessionPostBackKey', $Key);
		}
		return $Key;
	}

	/**
	 * Regenerate the session id.
	 *
	 * The old session id and the data associated to it should be destroyed.
	 * Sending a session id is not enought since someone with the old id would
	 * be able the claim the identity of the user.
	 *
	 * (the user should not lose his/her session data)
	 *
	 * @param Context $Context
	 */
	function RegenerateId($Context) {
		if (session_id()) {
			if (version_compare(phpversion(), '5.0.0', '>=')) {
				session_regenerate_id(true);
			} else {
				$SessionCopy = $_SESSION;
				session_destroy();
				session_id(md5(uniqid(rand(), true) . rand()));
				if ($Context->Configuration['SESSION_NAME']) {
					session_name($Context->Configuration['SESSION_NAME']);
				}
				session_start();
				setcookie(session_name(), session_id(), null,
					$Context->Configuration['COOKIE_PATH'],
					$Context->Configuration['COOKIE_DOMAIN'],
					($Context->Configuration['HTTP_METHOD'] === "https"));
				$_SESSION = $SessionCopy;
			}
		}
	}

	/**
	 * Start a session if required username/password exist in the system
	 *
	 * @param Context $Context
	 * @param Authenticator $Authenticator
	 * @param int $UserID
	 */
	function Start(&$Context, $Authenticator, $UserID = '0') {
		$this->StartSession($Context);

		// If the UserID is not explicitly defined (ie. by some vanilla-based login module),
		// retrieve the authenticated UserID from the Authenticator module.
		$this->UserID = ForceInt($UserID, 0);
		if ($this->UserID == 0) $this->UserID = $Authenticator->GetIdentity();

		// Now retrieve user information
		if ($this->UserID > 0) {
			$UserManager = $Context->ObjectFactory->NewContextObject($Context, 'UserManager');
			$this->User = $UserManager->GetSessionDataById($this->UserID);

			// If the session data retrieval failed for some reason, dump the user
			if (!$this->User) {
				$this->User = $Context->ObjectFactory->NewContextObject($Context, 'User');
				$this->User->Clear();
				$this->UserID = 0;
			}
		} else {
			$this->User = $Context->ObjectFactory->NewContextObject($Context, 'User');
			$this->User->Clear();
		}
	}

	/**
	 * Start the PHP session
	 *
	 * @param Context $Context
	 */
	function StartSession($Context) {
		if (!session_id()) {
			if (!empty($Context->Configuration['SESSION_NAME'])) {
				session_name($Context->Configuration['SESSION_NAME']);
			}
			$UseSsl = ($Context->Configuration['HTTP_METHOD'] === "https");
			if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
				session_set_cookie_params(0, $Context->Configuration['COOKIE_PATH'],
					$Context->Configuration['COOKIE_DOMAIN'], $UseSsl, true);
			} else {
				session_set_cookie_params(0, $Context->Configuration['COOKIE_PATH'],
					$Context->Configuration['COOKIE_DOMAIN'], $UseSsl);
			}
			session_start();
		}
	}

}
?>