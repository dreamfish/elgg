<?php
/**
 * Default interface for user authentication.
 * Applications utilizing this file: Vanilla;
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
 
 // load Elgg constants
 require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/constants.php');

/**
 * Default interface for user authentication. This class may be
 * replaced with another using the "AUTHENTICATION_MODULE"
 * and "AUTHENTICATION_CLASS" configuration settings.
 * @package People
 */
 
 // fake Elgg user class to get unserialise to work
	 class ElggUser {
	 	protected $attributes;
	 	public function toArray() {
	 		return (array) $this->attributes;
	 	}
	}
	
class Authenticator {
 
	/**
	 * @var Context
	 */
	var $Context;

	/**
	 * @var PeoplePasswordHash
	 */
	var $PasswordHash;

	// Returning '0' indicates that the username and password combination weren't found.
	// Returning '-1' indicates that the user does not have permission to sign in.
	// Returning '-2' indicates that a fatal error has occurred while querying the database.
	function Authenticate($Username, $Password, $PersistentSession) {
		// Validate the username and password that have been set
		$UserID = 0;
		$UserManager = $this->Context->ObjectFactory->NewContextObject(
			$this->Context, 'UserManager');
		$User = $UserManager->GetUserCredentials(0, $Username);

		if (!$User === null) {
			$UserID = -2;
		} elseif ($User) {
			if ($User->VerificationKey == '') $User->VerificationKey = DefineVerificationKey();

			if ($this->PasswordHash->CheckPassword($User, $Password)) {
				if (!$User->PERMISSION_SIGN_IN) {
					$UserID = -1;
				} else {
					$UserID = $User->UserID;
					$VerificationKey = $User->VerificationKey;

					// 1. Update the user's information
					$UserManager->UpdateUserLastVisit($UserID, $VerificationKey);

					// 2. Log the user's IP address
					$UserManager->AddUserIP($UserID);

					// 3. Assign the session value
					$this->AssignSessionUserID($UserID);

					// 4. Set the 'remember me' cookies
					if ($PersistentSession) $this->SetCookieCredentials($UserID, $VerificationKey);
				}
			}
		}
		return $UserID;
	}

	function Authenticator(&$Context) {
		$this->Context = &$Context;
		$this->PasswordHash = $this->Context->ObjectFactory->NewContextObject(
				$this->Context, 'PeoplePasswordHash');
	}

	function DeAuthenticate() {
		$this->Context->Session->Destroy();

		// Destroy the cookies as well
		$Cookies = array(
			$this->Context->Configuration['COOKIE_USER_KEY'],
			$this->Context->Configuration['COOKIE_VERIFICATION_KEY']);
		$UseSsl = ($this->Context->Configuration['HTTP_METHOD'] === "https");
		$HttpOnly = (array_key_exists('HTTP_ONLY_COOKIE', $this->Context->Configuration)
			&& $this->Context->Configuration['HTTP_ONLY_COOKIE']);
		foreach($Cookies as $Cookie) {
			// PHP 5.2.0 required for HTTP only parameter of setcookie()
			if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
				setcookie($Cookie,
					' ',
					time()-3600,
					$this->Context->Configuration['COOKIE_PATH'],
					$this->Context->Configuration['COOKIE_DOMAIN'],
					$UseSsl, // Secure connections only
					$HttpOnly); // HTTP only
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
			/*$EncryptedUserID = ForceIncomingCookieString($this->Context->Configuration['COOKIE_USER_KEY'], '');
			$VerificationKey = ForceIncomingCookieString($this->Context->Configuration['COOKIE_VERIFICATION_KEY'], '');
			$UserManager = $this->Context->ObjectFactory->NewContextObject(
				$this->Context, 'UserManager');

			$UserID = $this->ValidateVerificationKey($UserManager, $EncryptedUserID, $VerificationKey);*/
			
            if (isset($_COOKIE[ELGG_VF_ELGG_AUTH_COOKIE_NAME])) {
                $session_code = $_COOKIE[ELGG_VF_ELGG_AUTH_COOKIE_NAME];
                $sessions = ELGG_DB_PREFIX . 'users_sessions';
                $rs = $this->ElggDbQuery("SELECT data FROM  $sessions ".
                                 "WHERE session = '$session_code' ORDER BY ts DESC;");
                if(count($rs) > 0) {
                    $row = $rs[0];
                    $session_data = @$this->UnserialiseSession($row['data']);
                    // step two: get the user data
                    // get a UserManager
					$um = $this->Context->ObjectFactory->NewContextObject($this->Context, 'UserManager');
                    
                    if (isset($session_data['user'])) {
                    	$elgg_user = $session_data['user']->toArray();
                    	$username = $elgg_user['username'];
                        $UserID = $um->GetUserIdByName($username);
                        if (!$UserID) {
                        	// this is the first time this person has logged in
                    		// create the Vanilla account
                    		$name = $elgg_user['name'];
                    		$email = $elgg_user['email'];
                    		
                    		$UserID = $this->CreateVanillaAccount($um,$username,$name,$email);
                    	}
                    }

        			if ($UserID > 0) {
        				// 1. Update the user's information
        				$um->UpdateUserLastVisit($UserID);
        
        				// 2. Log the user's IP address
        				$um->AddUserIP($UserID);
        				
        				// 3. Set the UserID in the session so the Vanilla
        				// machinery can work        
          				$this->AssignSessionUserID($UserID);
          				
          				// 4. Set the Icon
          				// It would be good to do this only at account creation time, 
          				// but that does not deal with the initial admin user
          				// So this is here for now
						
						//$icon = FormatStringForDatabaseInput(ELGG_URL.'mod/profile/icondirect.php?size=small&username='.$username);
						$icon = FormatStringForDatabaseInput($this->GetElggIconURL($username));
						$query = "UPDATE LUM_User SET Icon = '$icon' WHERE UserID = $UserID";
						$this->Context->Database->Execute($query, 'Authenticator', 'GetIdentity', 'An database error occurred while attempting to set a user icon.');
          				
        			}
    			}
			}
		}
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

	/**
	 * Log user ip
	 *
	 * @deprecated
	 * @param int $UserID
	 */
	function LogIp($UserID) {
		if ($this->Context->Configuration['LOG_ALL_IPS']) {
			$UserManager = $this->Context->ObjectFactory->NewContextObject(
				$this->Context, 'UserManager');
			$UserManager->AddUserIP($UserID);
		}
	}

	/**
	 * Set cookies used for persistent "Session"
	 *
	 * If $Configuration['ENCRYPT_COOKIE_USER_KEY'] is True (in conf/settings.php),
	 * the UserID will be encrypted. In most cases you should be encrypted
	 *
	 * @param int $CookieUserID
	 * @param string $VerificationKey
	 */
	function SetCookieCredentials($CookieUserID, $VerificationKey) {
		// Note: 2592000 is 60*60*24*30 or 30 days

		if (array_key_exists('ENCRYPT_COOKIE_USER_KEY', $this->Context->Configuration)
			&& $this->Context->Configuration['ENCRYPT_COOKIE_USER_KEY']
		) {
			$CookieUserID = md5($CookieUserID);
		}

		$Cookies = array(
			$this->Context->Configuration['COOKIE_USER_KEY'] => $CookieUserID,
			$this->Context->Configuration['COOKIE_VERIFICATION_KEY'] => $VerificationKey);

		$UseSsl = ($this->Context->Configuration['HTTP_METHOD'] === "https");
		$HttpOnly = (array_key_exists('HTTP_ONLY_COOKIE', $this->Context->Configuration)
			&& $this->Context->Configuration['HTTP_ONLY_COOKIE']);
		foreach($Cookies as $Name => $Value) {
			// PHP 5.2.0 required for HTTP only parameter of setcookie()
			if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
				setcookie($Name,
					$Value,
					time()+2592000,
					$this->Context->Configuration['COOKIE_PATH'],
					$this->Context->Configuration['COOKIE_DOMAIN'],
					$UseSsl, // Secure connections only
					$HttpOnly); // HTTP only
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

	/**
	 * Update user last visit
	 *
	 * @deprecated
	 * @param int $UserID
	 * @param string $VerificationKey
	 */
	function UpdateLastVisit($UserID, $VerificationKey = '') {
		$UserManager = $this->Context->ObjectFactory->NewContextObject(
			$this->Context, 'UserManager');
		$UserManager->UpdateUserLastVisit($UserID, $VerificationKey);
	}

	/**
	 * Validate user's Verification Key
	 *
	 * Return user's id
	 *
	 * @param UserManager $UserManager
	 * @param string $EncryptedUserID
	 * @param string $VerificationKey
	 * @return int
	 */
	function ValidateVerificationKey($UserManager, $EncryptedUserID, $VerificationKey) {
		$EncryptedUserID = ForceString($EncryptedUserID, '');
		if ($EncryptedUserID && $VerificationKey) {
			$UserIDs = $UserManager->GetUserIdsByVerificationKey($VerificationKey);
			foreach ($UserIDs as $UserID) {
				// For backward compatibility, the UserID might not be encrypted
				if ($EncryptedUserID == $UserID
					|| $EncryptedUserID == md5($UserID)
				) {
					return $UserID;
				}
			}
		}
		return 0;
	}
	
	/**
     *  NEW FUNCTION
     *
     *  Unserialise session data
     *
     *  @param string $data
     *  @return array - the session data
     *  
     */
    
    private function UnserialiseSession($data) {
        $vars=preg_split('/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff^|]*)\|/',
                  $data,-1,PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        for($i=0; $vars[$i]; $i++) $result[$vars[$i++]]=unserialize($vars[$i]);
        return $result;
    }
    
    private function GetUseridFromUsername($username) {
        $query = "SELECT UserID FROM lum_user WHERE Name ='".mysql_real_escape_string(trim($username))."'";
        $result = $this->Context->Database->Execute($query, 'Authenticator', 'GetUseridFromUsername', 'An database error occurred while attempting to get the UserID.');
        $row = $this->Context->Database->GetRow($result);
        if ($row) {
        	return $rows['UserID'];
        }
    }
    
    /**
     *  NEW FUNCTION
     *
     *  This utility method actually does the lookup in the elgg
     *  database.
     *
     *  @param string $query - the query string
     *  @return array $rows - the resulting rows
     *  
     */
    private function ElggDbQuery($query) {
        
        if(!$cnx = mysql_connect(ELGG_DB_HOST, ELGG_DB_USER, ELGG_DB_PASSWORD))
    	{
    	    error_log('Could not connect to database: ' . mysql_error()); 
    	}
    
    	if(!mysql_select_db(ELGG_DB_NAME, $cnx))
    	{
    	    
    	    error_log('Could not select database: ' . mysql_error());
    	}
    
    	if(!$rs = mysql_query($query, $cnx))
    	{
    	    error_log('Could not execute query: ' . mysql_error());
    	}
    	
    	$rows = array();
    	
    	while ($row = mysql_fetch_assoc($rs))
    	{
    	    $rows[] = $row;
    	}
    	return $rows;
	}
	
	// TODO: look into whether there is a more Vanilla-like way to create accounts	
	private function CreateVanillaAccount($um,$username,$name,$email) {
		$user = $this->Context->ObjectFactory->NewContextObject($this->Context, 'User');
		$user->Name = FormatStringForDatabaseInput($username);
		$user->FirstName = FormatStringForDatabaseInput($name);
		$user->LastName = '';
		if (!trim($email)) {
			// no email address available, so fake one
			$email = $username.'@NoEmailAddressAvailable.org';
		}
		$user->Email = FormatStringForDatabaseInput($email);
		// create a fake password to satisfy Vanilla
		$user->NewPassword = $this->rand_str(10);
		$user->ConfirmPassword = $user->NewPassword;
		// assume that Elgg users have already agreed to the Vanilla terms
		$user->AgreeToTerms = true;
		// Make this user a
		// tell Vanilla to create the user
		
		// the next line is a kludge because an error results otherwise
		$this->Context->Session->User = $user;
		
		$um->CallDelegate('Constructor');
		$um->CallDelegate('PreCreateUser');
		// kludge to prevent Vanilla from forcing a login
		$um->Context->Configuration['ALLOW_IMMEDIATE_ACCESS'] = 1;
		$um->Context->Configuration['DEFAULT_ROLE'] = '3';
		
		// OK, ask Vanilla to create the user
		$um->CreateUser($user);

		$user_id = $um->GetUserIdByName($username);
				
		// all done
		return $user_id;
	}
	
	private function SanitiseString($string) {
        	return mysql_real_escape_string(trim($string));
    }
    
    private function GetElggIconURL($username) {
    	$ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL,ELGG_URL."mod/vanillaforum/get_icon_url.php");
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS,array('username'=>$username));
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	    
	    $icon_url = curl_exec ($ch);
	    curl_close ($ch); 
	    
	    return $icon_url;
    }

    private function rand_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
{
    // Length of character list
    $chars_length = (strlen($chars) - 1);

    // Start our string
    $string = $chars{rand(0, $chars_length)};
   
    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        // Grab a random character from our list
        $r = $chars{rand(0, $chars_length)};
       
        // Make sure the same two characters don't appear next to each other
        if ($r != $string{$i - 1}) $string .=  $r;
    }
   
    // Return the string
    return $string;
}
}
?>