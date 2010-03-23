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
* Description: File used by Dynamic Data Management object to change the order of categories
*/

include('../appg/settings.php');
include('../appg/init_ajax.php');

$PostBackKey = ForceIncomingString('PostBackKey', '');
if ($PostBackKey == ''
	|| $PostBackKey !== $Context->Session->GetCsrfValidationKey()
) {
	die($Context->GetDefinition('ErrPostBackKeyInvalid'));
}

if (!$Context->Session->User->Permission('PERMISSION_SORT_CATEGORIES')) {
	die($Context->GetDefinition('ErrPermissionSortCategories'));
}

$Sql = 'update '. GetTableName('Category', $DatabaseTables, $Configuration["DATABASE_TABLE_PREFIX"])
	. ' set ' . $DatabaseColumns['Category']['Priority'] . " = '//1' where "
	. $DatabaseColumns['Category']['CategoryID'] ." = '//2';";

$SortOrder = ForceIncomingArray('CategoryID', array());
$ItemCount = count($SortOrder);
for ($i = 0; $i < $ItemCount; $i++) {
	$CatID = ForceInt($SortOrder[$i], null);
	if ($CatID !== null) {
		$ExecSql = str_replace(array('//1', '//2'), array($i, $CatID), $Sql);
		$Context->Database->Execute($ExecSql, 'AJAX', 'ReorderCategories', 'Failed to reorder categories', 0);
	}
}
$Context->Unload();
?>