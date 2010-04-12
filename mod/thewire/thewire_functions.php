<?php
 
	function get_entities_replies($username= "",$type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid = null, $timelower = 0, $timeupper = 0)
	{
		global $CONFIG;
		
		if ($subtype === false || $subtype === null || $subtype === 0)
			return false;
		
		if ($order_by == "") $order_by = "e.time_created desc";
		$order_by = sanitise_string($order_by);
		$username = sanitise_string($username);
		$limit = (int)$limit;
		$offset = (int)$offset;
		$site_guid = (int) $site_guid;
		$timelower = (int) $timelower;
		$timeupper = (int) $timeupper;
		if ($site_guid == 0)
			$site_guid = $CONFIG->site_guid;
			
		if(!empty($username))
			$username = "@$username";
				
		$where = array();
		
		if (is_array($subtype)) {			
			$tempwhere = "";
			if (sizeof($subtype))
			foreach($subtype as $typekey => $subtypearray) {
				foreach($subtypearray as $subtypeval) {
					$typekey = sanitise_string($typekey);
					if (!empty($subtypeval)) {
						$subtypeval = (int) get_subtype_id($typekey, $subtypeval);
					} else {
						$subtypeval = 0;
					}
					if (!empty($tempwhere)) $tempwhere .= " or ";
					$tempwhere .= "(e.type = '{$typekey}' and e.subtype = {$subtypeval})";
				}								
			}
			if (!empty($tempwhere)) $where[] = "({$tempwhere})";
			
			
		} else {
		
			$type = sanitise_string($type);
			if ($subtype !== "")
				$subtype = get_subtype_id($type, $subtype);
			
			if ($type != "")
				$where[] = "e.type='$type'";
			if ($subtype!=="")
				$where[] = "e.subtype=$subtype";
				
		}
		
		if ($owner_guid != "") {
			if (!is_array($owner_guid)) {
				$owner_array = array($owner_guid);
				$owner_guid = (int) $owner_guid;
			//	$where[] = "owner_guid = '$owner_guid'";
			} else if (sizeof($owner_guid) > 0) {
				$owner_array = array_map('sanitise_int', $owner_guid);
				// Cast every element to the owner_guid array to int
			//	$owner_guid = array_map("sanitise_int", $owner_guid);
			//	$owner_guid = implode(",",$owner_guid);
			//	$where[] = "owner_guid in ({$owner_guid})";
			}
			if (is_null($container_guid)) {
				$container_guid = $owner_array;
			}
		}
		if ($site_guid > 0)
			$where[] = "e.site_guid = {$site_guid}";

		if (!is_null($container_guid)) {
			if (is_array($container_guid)) {
				foreach($container_guid as $key => $val) $container_guid[$key] = (int) $val;
				$where[] = "e.container_guid in (" . implode(",",$container_guid) . ")";
			} else {
				$container_guid = (int) $container_guid;
				$where[] = "e.container_guid = {$container_guid}";
			}
		}
		if ($timelower)
			$where[] = "e.time_created >= {$timelower}";
		if ($timeupper)
			$where[] = "e.time_created <= {$timeupper}";
			
		if (!$count) {
			$query = "SELECT e.*,o.* from {$CONFIG->dbprefix}entities e, {$CONFIG->dbprefix}objects_entity o where ";
		} else {
			$query = "SELECT count(e.guid) as total from {$CONFIG->dbprefix}entities e, {$CONFIG->dbprefix}objects_entity o where ";
		}
		foreach ($where as $w)
			$query .= " $w and ";
		$query .= get_access_sql_suffix(); // Add access controls
		$query .= " and e.guid=o.guid AND o.description LIKE '%$username%' ";
		
		if (!$count) {
			$query .= " order by $order_by";
			if ($limit) $query .= " limit $offset, $limit"; // Add order and limit
			$dt = get_data($query, "entity_row_to_elggstar");
			return $dt;
		} else {
			$total = get_data_row($query);
			return $total->total;
		}
		
		/*

		SELECT e.*,o.* from elggentities e, elggobjects_entity o 
		where  e.type='object' and  e.subtype=8 and  e.site_guid = 1 and ( (1 = 1)  and e.enabled='yes') AND e.guid=o.guid AND o.description LIKE '%admin%'
		order by time_created desc limit 0, 10
			
		* */
	}
	
	function list_entities_replies($username= "", $type= "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = false, $pagination = true) {
		
		$offset = (int) get_input('offset');
		$count = get_entities_replies($username, $type, $subtype, $owner_guid, "", $limit, $offset, true);
		$entities = get_entities_replies($username, $type, $subtype, $owner_guid, "", $limit, $offset);

		return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle, $pagination);
		
	}
?>