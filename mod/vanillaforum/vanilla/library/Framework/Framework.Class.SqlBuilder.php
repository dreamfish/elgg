<?php
/**
 * Class that builds a string of sql to be executed.
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
 * @package Framework
 * @version 1.1.7
 */


/**
 * Builds a string of sql to be executed.
 * @package Framework
 */
class SqlBuilder {
	/**
	* String of select fields
	* @access public
	* @var string
	*/
	var $Fields;

	/**
	* Array of field name/value pairs for inserting/updating
	* @access public
	* @var array
	*/
	var $FieldValues;

	/**
	* Associative array with information about the main table in the statement
	* @access public
	* @var array
	*/
	var $MainTable;

	/**
	* String of join clauses
	* @access public
	* @var string
	*/
	var $Joins;

	/**
	* Array of where clause parameters
	* @access public
	* @var array
	*/
	var $Wheres;

	/**
	* String of group by fields
	* @access public
	* @var string
	*/
	var $GroupBys;

	/**
	* String of order by clauses
	* @access public
	* @var string
	*/
	var $OrderBys;

	/**
	* Limit for a select
	* @access public
	* @var string
	*/
	var $Limit;

	/**
	* The name of this class
	* @access public
	* @var string
	*/
	var $Name;

	/**
	* Prefix all tables with this string
	* @access public
	* @var string
	*/
	var $TablePrefix;

	/**
	* An alias -> Key map of tables in the query
	* @access public
	* @var array
	*/
	var $TableMap;

	/**
	* Context
	* @access public
	* @var Context
	*/
	var $Context;


	/**
	* Generates a CASE Statement
	*
	* @param string $CaseTableAlias Table reference
	* @param string $CaseField Field used by the CASE statement
	* @param string $CaseFieldAlias Reference of the resulting data set
	* @param array $CaseArray Array of WHEN...THEN clauses
	* @param string $ElseValue This provides a default result in case none of the expressions in the WHEN...THEN clauses evaluate to true.
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->AddCaseSelect('Category', 'CategoryID','Policy',
	*    array(0 => array("WhenValue" =>1, "ThenValue" =>'"This is an adult oriented category. 18 only."'),
	*          1=>array("WhenValue" =>2, "ThenValue" =>'"This is for females only category."')),
	*          '"OK to view."');
	* </code>
	* This will generate the following Mysql query
	* <code>
	* CASE Category.CategoryID
	*   WHEN 1 THEN "This is an adult oriented category. 18 only."
	*   WHEN 2 THEN "This is for females only category."
	*   ELSE "OK to view."
	* END AS Policy
	* </code>
	*
	* <b>Complete examples:</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function AddCaseSelect($CaseTableAlias, $CaseField, $CaseFieldAlias, $CaseArray, $ElseValue = 'null') {
		if ($this->Fields != '') $this->Fields .= ', ';
		$this->Fields .= 'CASE '.$CaseTableAlias.'.'.$this->Context->DatabaseColumns[$this->TableMap[$CaseTableAlias]][$CaseField];
		for ($i = 0; $i < count($CaseArray); $i++) {
			$this->Fields .= ' WHEN '.$CaseArray[$i]['WhenValue'].' THEN '.$CaseArray[$i]['ThenValue'];
		}
		$this->Fields .= ' ELSE '.$ElseValue.' END';
		$this->Fields .= ' AS '.$CaseFieldAlias;
	}

	/**
	* Used for inserting/updating field values
	*
	* @param string $FieldName Field name that needs to be inserted or updated
	* @param string $FieldValue Field value that needs to be inserted or updated
	* @param bool $QuoteValue Whether the values should be wrapped with quotes, use '0' for ints and expressions
	* @param string $Function predefined functions like NOW()
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->AddFieldNameValue('AuthUserID', 15, 0);
	* $sql->AddFieldNameValue('FirstCommentID', 15, 0);
	* $sql->AddFieldNameValue('Name', 'Test post');
	* $sql->AddFieldNameValue('DateCreated', '', 0, NOW);
	* $sql->AddFieldNameValue('CategoryID', 3, 0);
	* </code>
	* This will generate the following Mysql query
	* <code>
	* INSERT INTO LUM_Discussion (AuthUserID, FirstCommentID, Name, DateCreated, CategoryID)
	* VALUES (15, 15, 'Test post', NOW(), 3)
	* </code>
	* <b>Complete examples:</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function AddFieldNameValue($FieldName, $FieldValue = '', $QuoteValue = 1, $Function = '') {
		if ($QuoteValue) $FieldValue = "'".$FieldValue."'";
		if ($Function != '') $FieldValue = $Function.'('.$FieldValue.')';
		$this->FieldValues[$FieldName] = $FieldValue;
	}

	/**
	* Generates a GROUP BY statement
	*
	* @param string $Field Field name to group by
	* @param string $TableAlias Table reference
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->AddGroupBy ('CommentID','Comment');
	* </code>
	* This will generate the following Mysql query
	* <code>
	* GROUP BY Comment.CommentID
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function AddGroupBy($Field, $TableAlias) {
		if (is_array($Field)) {
			$FieldCount = count($Field);
			$i = 0;
			for ($i = 0; $i < $FieldCount; $i++) {
				$this->AddGroupBy($Field[$i],$TableAlias);
			}
		} else {
			if ($Field != '') {
				if ($TableAlias != '') $Field = $TableAlias.'.'.$this->Context->DatabaseColumns[$this->TableMap[$TableAlias]][$Field];
				if ($this->GroupBys != '') $this->GroupBys .= ', ';
				$this->GroupBys .= $Field;
			}
		}
	}

	/**
	* Generates a JOIN statement
	*
	* @param string $NewTable 'Other' table used in the join
	* @param string $NewTableAlias 'Other' table alias
	* @param string $NewTableField 'Other' field
	* @param string $ExistingAlias 'Original' table used in the join
	* @param string $ExistingField 'Original' field
	* @param string $JoinMethod Join Method for eg, LEFT JOIN, RIGHT JOIN etc
	* @param string $AdditionalJoinMethods Additonal join methods
	* @param string $CustomTablePrefix Custom table prefix
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->AddJoin('Discussion', 'Discussion', 'FirstCommentID','Comment','CommentID','LEFT JOIN);
	* </code>
	* This will generate the following Mysql query
	* <code>
	* LEFT JOIN LUM_Discussion Discussion ON Comment.CommentID = Discussion.FirstCommentID
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function AddJoin($NewTable, $NewTableAlias, $NewTableField, $ExistingAlias, $ExistingField, $JoinMethod, $AdditionalJoinMethods = '', $CustomTablePrefix = '') {
		$CustomTablePrefix = $CustomTablePrefix == '' ? $this->TablePrefix : $CustomTablePrefix;
		$this->TableMap[$NewTableAlias] = $NewTable;

		$this->Joins .= $JoinMethod.' '.GetTableName($NewTable, $this->Context->DatabaseTables, $CustomTablePrefix).' '.$NewTableAlias
			.' ON '.$ExistingAlias.'.'.$this->Context->DatabaseColumns[$this->TableMap[$ExistingAlias]][$ExistingField].' = '.$NewTableAlias.'.'.$this->Context->DatabaseColumns[$NewTable][$NewTableField].' '.$AdditionalJoinMethods.' ';
	}

	/**
	* Generates a LIMIT statement
	*
	* @param int $Index 'Offset', where to begin the limit row count
	* @param int $Length Number of rows to be returned
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->AddLimit(0, 20);
	* </code>
	* This will generate the following Mysql query
	* <code>
	* LIMIT 0,20;
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function AddLimit($Index, $Length) {
		$this->Limit = ' LIMIT '.$Index.', '.$Length;
	}

	/**
	* Generates a ORDER BY statement
	*
	* @param $string|$array $FieldName Field name(s) to use for the ORDER BY statement
	* @param $string $TableAlias Table reference
	* @param $string $SortDirection Sort direction like ASC, DESC etc
	* @param $string $Function
	* @param $string $InnerFunction
	* @param $string $InnerFunctionParams
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->AddOrderBy ('DateCreated','Discussion', 'DESC' );
	* </code>
	* This will generate the following Mysql query
	* <code>
	* ORDER BY  Discussion.DateCreated DESC
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function AddOrderBy($FieldName, $TableAlias, $SortDirection = 'ASC', $Function = '', $InnerFunction = '', $InnerFunctionParams = '') {
		if ($this->OrderBys != '') $this->OrderBys .= ', ';
		if (is_array($FieldName)) {
			$i = 0;
			$NewOrderBys = '';
			$NewField = '';
			for ($i = 0; $i < count($FieldName); $i++) {
				if ($NewOrderBys != '') $NewOrderBys .= ', ';
				if ($TableAlias[$i] == '') {
					$NewField = $FieldName[$i];
				} else {
					$NewField = $TableAlias[$i].'.'.$this->Context->DatabaseColumns[$this->TableMap[$TableAlias[$i]]][$FieldName[$i]];
				}
				if ($InnerFunction != '') $NewField = $InnerFunction.'('.$NewField.$InnerFunctionParams.')';
				$NewOrderBys .= $NewField;
			}
			$this->OrderBys .= ' '.$Function.'('.$NewOrderBys.') '.$SortDirection;

		} else {
			$this->OrderBys .= ' '.$TableAlias.'.'.$this->Context->DatabaseColumns[$this->TableMap[$TableAlias]][$FieldName].' '.$SortDirection;
		}
	}
	/**
	* Generates a SELECT statement
	*
	* @param mixed $Field String or array of fields to be selected
	* @param string $TableAlias Table reference
	* @param string $FieldAlias Field alias. This is essentially what comes after the AS clause
	* @param string $Function Function to use, like ADDDATE(), COUNT() etc
	* @param int $GroupByThisField Group by this field. This will generate the GROUP BY clause for you.
	* @param string $FieldAddendum
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->AddSelect('DateCreated','Discussion','Date');
	* </code>
	* This will generate the following Mysql query
	* <code>
	* SELECT Discussion.DateCreated AS Date
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function AddSelect($Field, $TableAlias, $FieldAlias = '', $Function = '', $FunctionParameters = '', $GroupByThisField = '0', $FieldAddendum = '') {
		if (is_array($Field)) {
			$FieldCount = count($Field);
			$i = 0;
			for ($i = 0; $i < $FieldCount; $i++) {
				$this->AddSelect($Field[$i], $TableAlias, '', '', '', $GroupByThisField);
			}
		} else {
			if ($Field != '') {
				// $GroupByThisField = ForceBool($GroupByThisField, 0);
				if ($GroupByThisField) {
					if ($this->GroupBys != '') $this->GroupBys .= ', ';
					$this->GroupBys .= ($TableAlias != '' ? $TableAlias.'.'.$this->Context->DatabaseColumns[$this->TableMap[$TableAlias]][$Field] : $Field);
					// $this->AddGroupBy($Field, $TableAlias);
				}
				$QualifiedField = $Field;
				if ($TableAlias != '') $QualifiedField = $TableAlias.'.'.$this->Context->DatabaseColumns[$this->TableMap[$TableAlias]][$Field];
				if ($Function != '' && $FunctionParameters == '') $QualifiedField = $Function.'('.$QualifiedField.')';
				if ($Function != '' && $FunctionParameters != '') $QualifiedField = $Function.'('.$QualifiedField.', '.$FunctionParameters.')';
				if ($this->Fields != '') $this->Fields .= ', ';
				$this->Fields .= $QualifiedField.$FieldAddendum.' ';
				if ($FieldAlias != '') {
					$this->Fields .= ' AS '.$FieldAlias;
				} else {
					$this->Fields .= ' AS '.$Field;
				}
			}
		}
	}

	/**
	* Generates a WHERE statement
	*
	* @param string $TableAlias1 Table reference of the first field
	* @param string $Parameter1 The first field in the comparison operation
	* @param string $TableAlias2 Table reference of the second field
	* @param string $Parameter2 The second field in the comparison operation
	* @param string $Comparison operator == '=,>,<,in,<>,like' etc
	* @param string $AppendMethod The method by which this where should be attached to existing wheres
	* @param string $Function Function by which the field value should be passed by
	* @param int $QuoteParameter2 Whether the field values should be wrapped around quotes
	* @param int $StartWhereGroup Start the where group. It esentially adds a opening brackets, which should be closed by using EndWhereGroup()
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->AddWhere('Discussion','Closed','','0','=','', '', 1, 1);
	* $sql->AddWhere('Discussion','DateExpire','','NOW()','<','and', '', 0, 0);
	* $sql->AddWhere('Discussion','Active','','1','=','and', '', 1, 0);
	* $sql->EndWhereGroup();
	* </code>
	* This will generate the following Mysql query
	* <code>
	* WHERE (Discussion.Closed = '0' and Discussion.DateExpire < NOW() and Discussion.Active = '1' )
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function AddWhere($TableAlias1, $Parameter1, $TableAlias2, $Parameter2, $ComparisonOperator, $AppendMethod = 'AND', $Function = '', $QuoteParameter2 = '1', $StartWhereGroup = '0') {
		if (!is_array($this->Wheres)) $this->Wheres = array();
		$this->Wheres[] = array('TableAlias1' => $TableAlias1,
			'Param1' => $Parameter1,
			'TableAlias2' => $TableAlias2,
			'Param2' => $Parameter2,
			'ComparisonOperator' => $ComparisonOperator,
			'AppendMethod' => $AppendMethod,
			'Function' => $Function,
			'QuoteParameter2' => $QuoteParameter2,
			'StartWhereGroup' => $StartWhereGroup);
	}

	/**
	* Clears all members to their default values
	*
	* @return void
	*/
	function Clear() {
		$this->Fields = '';
		$this->FieldValues = array();
		$this->MainTable = array();
		$this->Joins = '';
		$this->Wheres = array();
		$this->GroupBys = '';
		$this->OrderBys = '';
		$this->Limit = '';
		$this->Name = 'SqlBuilder';
		$this->TablePrefix = $this->Context->Configuration['DATABASE_TABLE_PREFIX'];
		$this->TableMap = array();
	}

	/**
	* Generates a closing bracket for the WHERE clause
	*
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->EndWhereGroup();
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function EndWhereGroup() {
		$this->Wheres[] = ') ';
	}

	/**
	* Returns a DELETE statement
	*
	* @return string $sReturn
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->GetDelete();
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function GetDelete() {
		$sReturn = "DELETE ";
		$sReturn .= "FROM ".$this->MainTable["TableName"]." ";
		$sReturn .= $this->GetWheres(1);
		$this->WriteDebug($sReturn);
		return $sReturn;
	}

	/**
	* Returns an INSERT statement
	*
	* @param int $UseIgnore Whether to use the IGNORE option inside the INSERT statement
	* @return string $sReturn
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->GetInsert();
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function GetInsert($UseIgnore = "0") {
		$sReturn = "INSERT ";
		if ($UseIgnore == "1") $sReturn .= "IGNORE ";
		$sReturn .= "INTO ";
		$sReturn .= $this->MainTable["TableName"]." ";
		$Fields = "";
		$Values = "";
		while (list($name, $value) = each($this->FieldValues)) {
			if ($Fields != "") {
				$Fields .= ", ";
				$Values .= ", ";
			}
			$Fields .= $this->Context->DatabaseColumns[$this->TableMap[$this->MainTable['TableAlias']]][$name];
			$Values .= $value;
		}
		reset($this->FieldValues);
		$sReturn .= "($Fields) ";
		$sReturn .= "VALUES ($Values)";
		$this->WriteDebug($sReturn);
		return $sReturn;
	}

	/**
	* Returns a SELECT statement
	*
	* @param string $SelectPrefix Prefix before the SELECT statement
	*
	* @return string $sReturn
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->GetSelect();
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function GetSelect($SelectPrefix = "") {
		$sReturn = $SelectPrefix." SELECT ";
		$sReturn .= $this->Fields." ";

		// Build the from statement
		$sReturn .= "FROM ".$this->MainTable["TableName"]." ";

		$TableAlias = ForceString($this->MainTable["TableAlias"], "");
		if ($TableAlias != "") $sReturn .= $TableAlias." ";

		$sReturn .= $this->Joins." ";
		$sReturn .= $this->GetWheres();
		if ($this->GroupBys != "") $sReturn .= " GROUP BY ".$this->GroupBys;
		if ($this->OrderBys != "") $sReturn .= " ORDER BY ".$this->OrderBys;
		$sReturn .= $this->Limit;
		$this->WriteDebug($sReturn);
		return $sReturn;
	}

	/**
	* Returns an UPDATE statement
	*
	* @return string $sReturn
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->GetUpdate();
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function GetUpdate() {
		$sReturn = 'UPDATE '.$this->MainTable['TableName'].' SET ';
		$Delimiter = '';
		while (list($name, $value) = each($this->FieldValues)) {
			$sReturn .= $Delimiter.$this->Context->DatabaseColumns[$this->TableMap[$this->MainTable['TableAlias']]][$name].'='.$value;
			$Delimiter = ', ';
		}
		reset($this->FieldValues);
		$sReturn .= $this->GetWheres(1);
		$this->WriteDebug($sReturn);
		return $sReturn;
	}

	/**
	* Returns a WHERE clause
	*
	* @param int $ForUpdating
	* @return string $sWheres
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->GetWheres();
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function GetWheres($ForUpdating = 0) {
		$sWheres = '';
		$WhereCount = count($this->Wheres);
		if ($WhereCount > 0) {
			for ($i = 0; $i < $WhereCount; $i++) {
				if (is_array($this->Wheres[$i])) {
					$TableAlias1 = $this->Wheres[$i]['TableAlias1'];
					$Param1 = $this->Wheres[$i]['Param1'];
					$TableAlias2 = $this->Wheres[$i]['TableAlias2'];
					$Param2 = $this->Wheres[$i]['Param2'];
					$ComparisonOperator = $this->Wheres[$i]['ComparisonOperator'];
					$AppendMethod = $this->Wheres[$i]['AppendMethod'];
					$Function = $this->Wheres[$i]['Function'];
					$QuoteParameter2 = $this->Wheres[$i]['QuoteParameter2'];
					$StartWhereGroup = $this->Wheres[$i]['StartWhereGroup'];

					if ($ForUpdating) {
						if ($TableAlias1 != '') $Param1 = $this->Context->DatabaseColumns[$this->TableMap[$TableAlias1]][$Param1];
						if ($TableAlias2 != '') $Param2 = $this->Context->DatabaseColumns[$this->TableMap[$TableAlias2]][$Param2];
					} else {
						if ($TableAlias1 != '') $Param1 = $TableAlias1.'.'.$this->Context->DatabaseColumns[$this->TableMap[$TableAlias1]][$Param1];
						if ($TableAlias2 != '') $Param2 = $TableAlias2.'.'.$this->Context->DatabaseColumns[$this->TableMap[$TableAlias2]][$Param2];
					}

					$StartWhereGroup = ForceBool($StartWhereGroup, 0);

					// Add the append method if there is an existing clause
					if (!empty($sWheres) && substr($sWheres,strlen($sWheres)-1) != '(') {
						$sWheres .= $AppendMethod.' ';
					}
					if ($StartWhereGroup) $sWheres .= '(';
					if ($QuoteParameter2 == '1') $Param2 = "'".$Param2."'";
					if ($Function != '') $Param2 = $Function.'('.$Param2.')';

					// Do the comparison operation
					$sWheres .= $Param1.' '.$ComparisonOperator.' '.$Param2.' ';
				} else {
					$sWheres .= $this->Wheres[$i];
				}
			}
			$sWheres = ' WHERE '.$sWheres.' ';
		}
		// 2006-06-21 (mosullivan) I don't know why I cleared out this array, but it caused a bug
		// where I'd be using GetSelect during a test run and I'd want to echo the query before it
		// was executed. It would wipe out the where array so that when the actual query
		// was created and executed, there wouldn't be a where clause and it would return bogus data.

		// Clear out the array
		// $this->Wheres = array();
		// Return the where clause
		return $sWheres;
	}

	/**
	* Takes the current WHERE clause and wraps it in parentheses
	*
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->GroupWheres();
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function GroupWheres() {
		// Insert a paren in the first element of the wheres array and add one to the end as well
		array_unshift($this->Wheres, '(');
		$this->Wheres[] = ') ';
	}

	/**
	* Set main table
	*
	* @param string $TableName Table name
	* @param string $TableAlias Table reference
	* @param string $CustomTablePrefix
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->SetMainTable('Discussion','Discussion');
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function SetMainTable($TableName, $TableAlias = '', $CustomTablePrefix = '') {
		$CustomTablePrefix = $CustomTablePrefix == '' ? $this->TablePrefix : $CustomTablePrefix;
		$MapKey = $TableAlias == "" ? $TableName : $TableAlias;
		$this->TableMap[$MapKey] = $TableName;
		$this->MainTable = array("TableName" => GetTableName($TableName, $this->Context->DatabaseTables, $CustomTablePrefix), "TableAlias" => $TableAlias);
	}

	/**
	* Generates an opening bracket for the WHERE clause
	*
	* @param string $AppendMethod
	* @return void
	* <b>Usage example:</b>
	* <code>
	* $sql->StartWhereGroup();
	* </code>
	*
	* <b>Complete examples</b>
	* For complete workable examples check out the {@link http://lussumo.com/docs/doku.php?id=vanilla:development:sqlbuilder Sqlbuilder wiki page}
	*/
	function StartWhereGroup($AppendMethod = '') {
		$this->Wheres[] = ' ' . $AppendMethod . ' (';
	}

	/**
	* Constructor
	*
	* @param $Context
	*/
	function SqlBuilder(&$Context) {
		$this->Context = &$Context;
		$this->Clear();
	}


	/**
	* Generates DEBUG reports
	*
	* @param string $String
	* @return void
	*
	* <b>Usage example:</b>
	* <code>
	* $sql->WriteDebug();
	* </code>
	*/
	function WriteDebug($String) {
		if ($this->Context->Session->User) {
			if ($this->Context->Session->User->Permission("PERMISSION_ALLOW_DEBUG_INFO") && $this->Context->Mode == MODE_DEBUG) $this->Context->SqlCollector->Add($String);
		}
	}
}
?>