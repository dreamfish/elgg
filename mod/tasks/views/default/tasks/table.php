<?php

	/**
	 * Elgg tasks plugin index page
	 * 
	 * @package Elggtasks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

		global $CONFIG;
		$url = $CONFIG->wwwroot;
		
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}
			
	// List tasks
		$area2 = elgg_view_title(sprintf(elgg_echo('tasks:read'), $page_owner->name));
		set_context('search');
		$items = get_entities('object','tasks',page_owner(),'', 1000);
			
		
		//$area2.= '';
		
		set_context('tasks');
		
	// Format page
		//$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
		
	// Draw it
		//echo page_draw(elgg_echo('tasks:read'),$body);

?>
<style>
	th { cursor: pointer; }
</style>
<script src="<?php echo $url?>mod/tasks/js/jquery.tablesorter.min.js"></script>
<script>

Array.prototype.unique =
  function() {
    var a = [];
    var l = this.length;
    for(var i=0; i<l; i++) {
      for(var j=i+1; j<l; j++) {
        // If this[i] is found later in the array
        if (this[i] === this[j])
          j = ++i;
      }
      a.push(this[i]);
    }
    return a;
  };
  
$(function() { 


  
	$('#taskTable').tablesorter(); 
	var status = $.map($('.status'), function(item) { return $(item).text(); }).unique();
	var workers = $.map($('.worker'), function(item) { return $(item).text(); }).unique()
	$.each(status, function(idx, item) { $('#statusFilter').append(
			$('<option></option>').val(item).html(item)
		)
    });
    $.each(workers, function(idx, item) { $('#workerFilter').append(
			$('<option></option>').val(item).html(item)
		)
    });
    
    $('#statusFilter').change(function() {
		val = $(this).val();
		if (val == 'All')
		{
			$('.status').each(function() { $(this).parents('tr:first').show(); });
		}
		else
		{			
			$('.status').each(function() { $(this).parents('tr:first').hide(); });
			$('.status:contains("' + val + '")').each(function() { $(this).parents('tr:first').show(); });			
		}
    });
});
</script>
<div class="contentWrapper">
Filter Status: <select id="statusFilter"><option value="All">All</option> </select>
Filter Worker: <select id="workerFilter"><option value="All">All</option> </select>

<table style="width:100%" id="taskTable">
<thead>
	<tr>
		<th>name</th>
		<th>status</th>
		<th>assigned to</th>
		<th>type</th>
	</tr>
</thead>
<tbody>	
<?php foreach($items as $task) {
$metadata = get_metadata_for_entity ($task->guid);

$meta_arr = array();
foreach($metadata as $meta) {
	$meta_arr[$meta['name']] = $meta['value'];
}

$type = elgg_echo("tasks:task_type_{$task->task_type}");
$status = elgg_echo("tasks:task_status_{$task->status}");
if ($status != 'Closed') {
	$manage_link = "<a href=\"{$url}mod/tasks/manage.php?task=".$task->getGUID()."\">". $task->title ."</a>&nbsp;"; 
	$worker = get_entity($task->assigned_to);	
	
	echo "<tr><td>{$manage_link}</td><td class=\"status {$status}\">{$status}</td><td class=\"worker {$worker->name}\">{$worker->name}</td><td>$type</td></tr>";
}
}
?>
</tbody>
</table>
	
</div>