<?php
/**
 * A special authenticator for SQLite.
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
 * A special authenticator for SQLite.
 * @package People
 */
class Authenticator {
	var $Context;

	// Returning '0' indicates that the username and password combination weren't found.
	// Returning '-1' indicates that the user does not have permission to sign in.
	// Returning '-2' indicates that a fatal error has occurred while querying the database.
	function Authenticate($Username, $Password, $PersistentSession) {
		// Validate the username and password that have been set
		$Username = FormatStringForDatabaseInput($Username);
		$Password = FormatStringForDatabaseInput($Password);
		$UserID = 0;

		// Retrieve matching username/password values
		$Query = "select u.UserID, r.PERMISSION_SIGN_IN
			from ".$this->Context->Configuration['DATABASE_TABLE_PREFIX']."User u
			inner join ".$this->Context->Configuration['DATABASE_TABLE_PREFIX']."Role r
				on u.RoleID = r.RoleID
			where u.Name = '".$Username."'
				and u.Password = '".$Password."'";

		$UserResult = $this->Context->Database->Execute($Query,
			'Authenticator',
			'Authenticate',
			'An error occurred while attempting to validate your credentials');

		if (!$UserResult) {
			$UserID = -2;
		} elseif ($this->Context->Database->RowCount($UserResult) > 0) {
			$CanSignIn = 0;
			$EncryptedUserID = '';
			$VerificationKey = '';
			while ($rows = $this->Context->Database->GetRow($UserResult)) {
				$EncryptedUserID = md5($rows['UserID']);
				$VerificationKey = DefineVerificationKey();
				$UserID = ForceInt($rows['UserID'], 0);
				$CanSignIn = ForceBool($rows['PERMISSION_SIGN_IN'], 0);
			}
			if (!$CanSignIn) {
				$UserID = -1;
			} else {
				// Update the user's information
				$this->UpdateLastVisit($UserID, $VerificationKey);

				// Assign the session value
				$this->AssignSessionUserID($UserID);

				// Set the 'remember me' cookies
				if ($PersistentSession) $this->SetCookieCredentials($EncryptedUserID, $VerificationKey);
			}
		}
		return $UserID;
	}

	function Authenticator(&$Context) {
		$this->Context = &$Context;
	}

	function DeAuthenticate() {
		$this->Context->Session->Destroy();

		// Destroy the cookies as well
		$Cookies = array(
			$this->Context->Configuration['COOKIE_USER_KEY'],
			$this->Context->Configuration['COOKIE_VERIFICATION_KEY']);
		$UseSsl = ($this->Context->Configuration['HTTP_METHOD'] === "https");
		foreach($Cookies as $Cookie) {
			// PHP 5.2.0 required for HTTP only parameter of setcookie()
			if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
				setcookie($Cookie,
					' ',
					time()-3600,
					$this->Context->Configuration['COOKIE_PATH'],
					$this->Context->Configuration['COOKIE_DOMAIN'],
					$UseSsl, // Secure connections only
					1); // HTTP only
			} else {
				setcookie($Cookie,
					' ',
					time()-3600,
					$this->Context->Configuration['COOKIE_PATH'],
					$this->Context->Configuration['COOKIE_DOMAIN'],
					$UseSsl); // Secure connections only
			}
			unset($_COOKIE[$Cookie]);
		}
		return true;
	}

	function GetIdentity() {
		$UserID = $this->Context->Session->GetVariable(
			$this->Context->Configuration['SESSION_USER_IDENTIFIER'], 'int');
		if ($UserID == 0) {
			// UserID wasn't found in the session, so attempt to retrieve it from the cookies
			// Retrieve cookie values
			$EncryptedUserID = ForceIncomingCookieString($this->Context->Configuration['COOKIE_USER_KEY'], '');
			$VerificationKey = ForceIncomingCookieString($this->Context->Configuration['COOKIE_VERIFICATION_KEY'], '');

			if ($EncryptedUserID != '' && $VerificationKey != '') {

				// Compare against db values
				// Sadly, because this class is meant to be an interface for distributed objects, I can't use any of the error checking in the Lussumo Framework
				$Query = "select UserID
					from LUM_User
					where VerificationKey = '".FormatStringForDatabaseInput($VerificationKey)."'";

				$Result = $this->Context->Database->Execute($Query,
					'Authenticator',
					'GetIdentity',
					'An error occurred while attempting to validate your remember me credentials');

				if ($Result) {
					$UserID = 0;
					while ($rows = $this->Context->Database->GetRow($Result)) {
						if ($EncryptedUserID == md5($rows['UserID'])) {
							$UserID = ForceInt($rows['UserID'], 0);
							$EncryptedUserID = $rows['EncryptedUserID'];
							break;
						}
					}
					if ($UserID > 0) {
						// 1. Set a new verification key
						$VerificationKey = DefineVerificationKey();

						// 2. Update the user's information
						$this->UpdateLastVisit($UserID, $VerificationKey);

						// 3. Set the 'remember me' cookies
						$this->SetCookieCredentials($EncryptedUserID, $VerificationKey);

						// 4. Log the user's IP address
						$this->LogIp($UserID);
					}
				}
			}
		}

		// If it has now been found, set up the session.
		$this->AssignSessionUserID($UserID);
		return $UserID;
	}

	// All methods below this point are specific to this authenticator and
	// should not be treated as interface methods. The only required interface
	// properties and methods appear above.

	function AssignSessionUserID($UserID) {
		if ($UserID > 0) {
			$this->Context->Session->SetVariable(
				$this->Context->Configuration['SESSION_USER_IDENTIFIER'], $UserID);
		}
	}

	function LogIp($UserID) {
		if ($this->Context->Configuration['LOG_ALL_IPS']) {
			$Query = "insert into LUM_IpHistory
				(UserID, RemoteIp, DateLogged)
				values ('".$UserID."', '".GetRemoteIp(1)."', '".MysqlDateTime()."')";

			$this->Context->Database->Execute($Query,
				'Authenticator',
				'LogIp',
				'An error occurred while logging your IP address.',
				false); // fail silently
		}
	}

	function SetCookieCredentials($CookieUserID, $VerificationKey) {
		// Note: 2592000 is 60*60*24*30 or 30 days
		$Cookies = array(
			$this->Context->Configuration['COOKIE_USER_KEY'] => $CookieUserID,
			$this->Context->Configuration['COOKIE_VERIFICATION_KEY'] => $VerificationKey);
		$UseSsl = ($this->Context->Configuration['HTTP_METHOD'] === "https");
		foreach($Cookies as $Name => $Value) {
			// PHP 5.2.0 required for HTTP only parameter of setcookie()
			if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
				setcookie($Name,
					$Value,
					time()+2592000,
					$this->Context->Configuration['COOKIE_PATH'],
					$this->Context->Configuration['COOKIE_DOMAIN'],
					$UseSsl, // Secure connections only
					1); // HTTP only
			} else {
				setcookie($Name,
					$Value,
					time()+2592000,
					$this->Context->Configuration['COOKIE_PATH'],
					$this->Context->Configuration['COOKIE_DOMAIN'],
					$UseSsl); // Secure connections only
			}
		}
	}

	function UpdateLastVisit($UserID, $VerificationKey) {
		$Query = "update LUM_User
			set DateLastActive = '".MysqlDateTime()."',
				VerificationKey = '".$VerificationKey."',
				CountVisit = CountVisit + 1
			where UserID = ".$UserID;

		$this->Context->Database->Execute($Query,
				'Authenticator',
				'UpdateLastVisit',
				'An error occurred while updating your profile.',
				false); // fail silently
	}
}
?>