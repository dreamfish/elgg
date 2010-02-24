<?php
	/**
	 * User validation plugin.
	 * 
	 * @package pluginUserValidation
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ralf Fuhrmann, Euskirchen, Germany
	 * @copyright 2008 Ralf Fuhrmann, Euskirchen, Germany
	 * @link http://mysnc.de/
	 */

	$german = array(
	
		/**
		 * Default translations
		 */
		
		'uservalidation:admin:confirm:fail' => "Nutzerkonto konnte nicht aktiviert werden !",
		'uservalidation:admin:confirm:success' => "Nutzerkonto wurde aktiviert !",
		'uservalidation:admin:registerok' => "Deine Registrierung wird von einem Administrator dieser Seite überprüft. Über die Freischaltung Deines Nutzerkontos wirst Du per eMail informiert !", 
		'uservalidation:confirm:fail' => "Dein Nutzerkonto konnte nicht aktiviert werden ! Bitte versuche es erneut oder wenden Dich an den Seiten-Administrator !",
		'uservalidation:confirm:success' => "Dein Nutzerkonto wurde aktiviert. Du kannst Dich nun Anmelden !",
		'uservalidation:email:registerok' => "Zur Bestätigung Deiner eMail-Adresse wurde Dir eine eMail geschickt. Bitte Bestätige den Link in dieser eMail um den Registrierungsvorgang abzuschließen !", 

		/**
		 * eMail translations
		 */

		'uservalidation:adminmail:subject' => "Neue Nutzer-Registrierung : %s !",
		'uservalidation:adminmail:body' => "
Hallo Admin,
soeben hat sich der neue Nutzer %s (%s) registriert.
Sofern Du 'Überprüfung durch den Admin' aktiviert hast, stelle bitte sicher das Du den neuen Nutzer so schnell als möglich aktivierst !",
	
		'uservalidation:autodelete:subject' => "Es wurden automatisch einige Nutzer gelöscht !",
		'uservalidation:autodelete:body' => "
Hallo Admin,
die folgenden Nutzer wurden soeben automatisch gelöscht :
%s",
	
		'uservalidation:admin:validate:subject' => "%s ! Deine Registrierung wird geprüft !",
		'uservalidation:admin:validate:body' => "
Hallo %s,
Deine Registrierung wird von einem Administrator dieser Seite überprüft. Über die Freischaltung Deines Nutzerkontos wirst Du per eMail informiert.
%s",
	
		'uservalidation:email:validate:subject' => "%s ! Bitte bestätige Deine eMail-Adresse !",
		'uservalidation:email:validate:body' => "
Hallo %s,
Bitte verwende den folgenden Link zur Bestätigung Deiner eMail-Adresse um den Registrierungsvorgang abzuschließen :
%s",
	
		'uservalidation:success:subject' => "%s ! Dein Nutzerkonto wurde aktiviert !",
		'uservalidation:success:body' => "
Hallo %s,
Glückwunsch, Dein Nutzerkonto wurde soeben freigeschaltet.
Zur Anmeldung auf %s verwendete bitte den folgenden Link :
%s",


	);
	add_translation('de', $german);
	
	
	if (isadminloggedin())
	{
	

		$german = array(

			/**
			* Admin-Only translations
			*/

			'uservalidation:activate' => "Nutzer aktivieren",
			'uservalidation:autodelete' => "Tage, nach dem ein nicht aktiviertes Benutzerkonto gelöscht werden soll",
			'uservalidation:autodelete:no' => "keine automatische Löschung",
			'uservalidation:delete' => "Nutzer löschen", 
			'uservalidation:banned' => "Gesperrt",
			'uservalidation:method' => "Wie soll die Benutzerüberprüfung erfolgen",
			'uservalidation:method:none' => "keine Überprüfung",
			'uservalidation:method:bymail' => "Überprüfung per eMail",
			'uservalidation:method:byadmin' => "Überprüfung über einen Admin",
			'uservalidation:pendingusers' => "wartende Registrierungen",
			'uservalidation:registered' => "Registriert: ",
			'uservalidation:adminmail' => "Soll der Adminstrator bei Registrierung eines Nutzers eine eMail erhalten",
			'uservalidation:adminmail:every' => "bei jeder Registrierung",
			'uservalidation:adminmail:adminonly' => "nur dann, wenn der Administrator eingreifen muss",
			'uservalidation:waiting' => "Wartet auf aktivierung",

		);
		add_translation('de', $german);
	
	}
	
?>