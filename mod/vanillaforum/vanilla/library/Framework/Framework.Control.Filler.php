<?php
/**
 * The Filler control can be used to dump any custom template in the page.
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
 * @package Framework
 * @version 1.1.7
 */


/**
 * The Filler control can be used to dump any custom template in the page.
 * @package Framework
 */
class Filler extends PostBackControl {
	var $TemplateFile;
	var $Properties;

	function Filler(&$Context, $templateFile = '', $ValidActions = '') {
		$this->Name = 'Filler';
		$this->ValidActions = $ValidActions;
		$this->Constructor($Context);
		if ($ValidActions == '') $this->IsPostBack = 1;
		$this->Properties = array();
		if ($templateFile != '') $this->TemplateFile = $templateFile;
	}

	function Render() {
		if ($this->TemplateFile != '' && $this->IsPostBack) {
			$Template = ThemeFilePath($this->Context->Configuration, $this->TemplateFile);
			if (file_exists($Template)) {
				$this->CallDelegate('PreRender');
				include($Template);
				$this->CallDelegate('PostRender');
			}
		}
	}
}
?>