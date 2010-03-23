<?php
/*
* Copyright 2003 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*
* Description: File used by Dynamic Data Management object to fill autocomplete data on user input field
*/

include('../appg/settings.php');
include('../appg/init_ajax.php');

$Search = ForceIncomingString('Search', '');
$Search = urldecode($Search);
$Search = FormatStringForDatabaseInput($Search);
if ($Search != '') {
	$s = $Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
	$s->SetMainTable('User', 'u');
	$s->AddSelect('Name', 'u');
	$s->AddWhere('u', 'Name', '', $Search.'%', 'like');
	$s->AddOrderBy('Name', 'u', 'asc');
	$s->AddLimit(0,10);
	$ResultSet = $Context->Database->Select($s, 'Ajax', 'AutoComplete', 'An error occurred while retrieving autocomplete items.', 0);
	$Name = '';
	$Loop = 1;
	if ($ResultSet) {
		while ($row = $Context->Database->GetRow($ResultSet)) {
			if ($Loop > 1) echo ',';
			$Name = FormatStringForDisplay($row['Name'], 1);
			echo $Name;
			$Loop++;
		}
	}
}
$Context->Unload();
?>
