<?php
/**
 * Description: Check a directory content;
 * Used for integrity checking
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

/**
 * Directory scanner.
 *
 * Beta version! the protocol may change.
 *
 * @package Framework
 */
class DirectoryScanner {

	var $Reporter;
	var $BlackList = array('.', '..', '.svn');

	function DirectoryScanner(&$Reporter) {
		$this->Reporter =& $Reporter;
	}

	function Scan($DirPath) {
		$Handler = opendir($DirPath);
		$File = false;
		$FilePath = '';

		if ($Handler !== false) {
			while(($File = readdir($Handler)) !== false) {
				 if (!in_array($File, $this->BlackList)) {
				 	$FilePath = $DirPath . '/' . $File;
				 	if (is_dir($FilePath)) {
				 		$this->Scan($FilePath);
				 	} else {
				 		$this->Reporter->Add($FilePath);
				 	}
				 }
			}
			closedir($Handler);
			return true;
		} else {
			// @todo: add error message;
			return false;
		}
	}
}

/**
 * Generic Reporter for the directory scanner
 *
 * Beta version! the protocol may change.
 *
 * @package Framework
 */
class Reporter {

	var $Reader;

	function Reporter($Reader) {
		if (!$Reader) {
			$Reader = new FileReader();
		}
		$this->Reader= clone($Reader);
	}

	function Add($FilePath) {
		echo $FilePath . ":\n" . $this->GetFile($FilePath) . "\n-----------\n";
	}

	function GetFile($FilePath) {
		return $this->Reader->Read($FilePath);
	}
}

/**
 * Generic File Reader for the directory scanner
 *
 * Beta version! the protocol may change.
 *
 * @package Framework
 */
Class FileReader {
	function Read($FilePath) {
		$Handle = @fopen($FilePath, "r");
		if($Handle !== false) {
			$Content = @fread($Handle, filesize($FilePath));
			fclose($Handle);
			return $Content;
		} else {
			return false;
		}
	}
}
