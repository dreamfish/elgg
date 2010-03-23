<?php
/**
 * Description: Used to Check md5 signature of an installation;
 *
 *
 * Copyright 2008 Lussumo.com
 * This file is part of Lussumo's Software Library.
 * Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * The latest source code is available at www.lussumo.com
 * Contact Mark O'Sullivan at mark [at] lussumo [dot] com
 *
 * @author Damien Lebrun
 * @copyright 2008 Lussumo.com
 * @license http://lussumo.com/community/gpl.txt GPL 2
 * @package Framework
 * @version 1.1.7
 */

include_once 'Framework.Class.DirectoryScanner.php';

/**
 * Integrity Checker.
 *
 * Beta version! the protocol may change.
 *
 * examples:
 * <code>
 * // check signature of files in ./; ./appg/md5.csv is hold the signature to compare to
 * $Checker = new IntegrityChecker('./');
 * $Checker->Check('./appg/md5.csv');
 *
 * // To build the sgnatures:
 * $Checker = new IntegrityChecker('./');
 * $Checker->Save('./appg/md5.csv');
 * </code>
 * @package Framework
 */
class IntegrityChecker extends DirectoryScanner {

	/**
	 * Path to the file containing the list of filed and their signature
	 * @var string
	 */
	var $Source;

	/**
	 * Reference signature loaded from the source file
	 * @var array
	 */
	var $Ref = array();

	/**
	 * List of files for which the signature doesn't match
	 * @var array
	 */
	var $Errors = array();

	/**
	 * csv field delimiter for the signature list
	 * @var string
	 */
	var $Delimiter = ',';

	/**
	 * Constructor
	 *
	 * @param string $Source
	 * @param string $Root
	 * @param ReportMd5 $Reporter
	 * @return IntegrityChecker
	 */
	function IntegrityChecker($Root=null)	{
		$Reporter = new ReportMd5($Root);
		$this->DirectoryScanner($Reporter);
	}

	/**
	 * Scan a directory and save the md5 signature in a csv file
	 *
	 * @return boolean
	 */
	function Save($Source) {
		$this->Scan($this->Reporter->Root);

		$Handler = @fopen($Source, 'w');

		if ($Handler === false) {
			return false;
		}

		@fwrite($Handler, $this->Reporter->ReportAsCsv());

		@fclose($Handler);
		return true;
	}

	/**
	 * Check the csv list of signature against the signature of installed files
	 *
	 * @return boolean|null Return True if the check is successfull, False if it failed, Null if the list of signature is missing
	 */
	function Check($Source, $Reset=1) {
		if($this->LoadRef($Source) === false) {
			return null;
		}

		if ($Reset) {
			$this->Errors = array();
		}

		foreach ($this->Ref as $Name => $Md5) {
			$File = $this->Reporter->Root . '/' . $Name;
			if (file_exists($File)) {
				if ($Md5 !== $this->Reporter->GetFile($File)) {
					$this->Errors[$Name] = $Md5;
				}
			} else {
				$this->Errors[$Name] = '';
			}
		}

		return empty($this->Errors);
	}

	function ErrorsAsText() {
		$Errors = '';
		asort($this->Errors);
		foreach ($this->Errors as $Name => $Md5) {
			if (empty($Md5)) {
				$Errors .= "$Name is missing.\n";
			} else {
				$Errors .= "$Name might be corrupted.\n";
			}
		}
		return $Errors;
	}


	/**
	 * Load md5 singature references from the source file
	 *
	 * @return boolean
	 */
	function LoadRef($Source) {

		if (!file_exists($Source)) {
			return false;
		}
		$Handler = fopen($Source, 'r');
		if (!$Handler) {
			return false;
		}

		while (($Row = fgetcsv($Handler, 300, $this->Delimiter)) !== false) {
			if (count($Row) >= 2) {
				$this->Ref[$Row[0]] = $Row[1];
			}
		}

		fclose($Handler);

		return !empty($this->Ref);
	}
}

/**
 * Integrity Checker
 *
 * Beta version! the protocol may change.
 *
 * @package Framework
 */
class ReportMd5 extends Reporter {

	/**
	 * csv field delimiter for the signature list
	 * @var string
	 */
	var $Delimiter = ',';

	/**
	 * end of line for the signature list file
	 * @var string
	 */
	var $Eol = "\n";

	/**
	 * List of files and their signature
	 * @var array
	 */
	var $Report = array();

	/**
	 * Path to folder scanned
	 * @var string
	 */
	var $Root;

	/**
	 * String length of folder scanned
	 * @access private
	 * @var int
	 */
	var $_RootLength;

	/**
	 * Constructor
	 *
	 * @param string $Root Folder scanned
	 * @return ReportMd5
	 */
	function ReportMd5($Root = null) {
		if ($Root === null) {
			$Root = './';
		}
		$this->Reporter(new FileReadMd5());
		$this->Root = realpath($Root);
		$this->_RootLength = strlen($this->Root);
	}

	/**
	 * Add a file and its signature to the report
	 * @param string $FilePath
	 */
	function Add($FilePath) {
		$Md5 = $this->GetFile($FilePath);
		$Name = $this->FormatPath($FilePath);
		$this->Report[$Name] = $Md5;
	}

	/**
	 * Format path file
	 *
	 * Force the unix type of path and make it relative to scanned folder
	 *
	 * @param string $FilePath
	 * @return string
	 */
	function FormatPath($FilePath) {
		$FilePath = realpath($FilePath);
		$FilePath = substr($FilePath, $this->_RootLength);
		$FilePath = ltrim($FilePath, '\\/');
		$FilePath = str_replace('\\', '/', $FilePath);
		return $FilePath;
	}

	/**
	 * Return list of files and signature as a csv string
	 * @return string
	 */
	function ReportAsCsv() {
		$Csv = '';
		foreach ($this->Report as $Name => $Md5) {
			$Csv .= $Name . $this->Delimiter . $Md5 . $this->Eol;
		}
		return $Csv;
	}

	/**
	 * Return the report as string
	 * @return string
	 */
	function __toString() {
		$String = '';
		foreach ($this->Report as $Name => $Md5) {
			$String = "$Name: \n$Md5\n-----\n \n";
		}
		return $String;
	}
}


/**
 * File reader - Read file and return md5 hash of the content
 *
 * Beta version! the protocol may change.
 *
 * @package Framework
 */
class FileReadMd5 extends FileReader {
	/**
	 * Return md5 signature of a file
	 *
	 * @param string $FilePath
	 * @return string
	 */
	function Read($FilePath) {
		$Content = parent::Read($FilePath);
		if ($Content !== false) {
			$Content = md5($Content);
		}
		return $Content;
	}
}