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
	var status = $.map($('.status'), function(item) { return $(item).text(); }).unique();
	var workers = $.map($('.worker'), function(item) { return $(item).text(); }).unique();
  var types = $.map($('.type'), function(item) { return $(item).text(); }).unique();

	  $.each(status, function(idx, item) { $('#statusFilter').append(
			$('<option></option>').val(item).html(item)
		)
    });
    $.each(workers, function(idx, item) { $('#workerFilter').append(
			$('<option></option>').val(item).html(item)
		)
    });
    $.each(types, function(idx, item) { $('#typeFilter').append(
			$('<option></option>').val(item).html(item)
		)
    });
    
    $('#statusFilter').change(function() {
		val = $(this).val();
		if (val == 'All')
		{
			$('.status').each(function() { $(this).parents('.task:first').show(); });
		}
		else
		{			
			$('.status').each(function() { $(this).parents('.task:first').hide(); });
			$('.status:contains("' + val + '")').each(function() { $(this).parents('.task:first').show(); });			
		}
    });
    
	$('#workerFilter').change(function() {
		val = $(this).val();
		if (val == 'All')
		{
			$('.worker').each(function() { $(this).parents('.task:first').show(); });
		}
		else
		{			
			$('.worker').each(function() { $(this).parents('.task:first').hide(); });
			$('.worker:contains("' + val + '")').each(function() { $(this).parents('.task:first').show(); });			
		}
    });

	$('#typeFilter').change(function() {
		val = $(this).val();
		if (val == 'All')
		{
			$('.type').each(function() { $(this).parents('.task:first').show(); });
		}
		else
		{			
			$('.type').each(function() { $(this).parents('.task:first').hide(); });
			$('.type:contains("' + val + '")').each(function() { $(this).parents('.task:first').show(); });			
		}
    });


});
</script>
<div class="contentWrapper">
Filter Type: <select id="typeFilter"><option value="All">All</option> </select>
Filter Status: <select id="statusFilter"><option value="All">All</option> </select>
Filter Worker: <select id="workerFilter"><option value="All">All</option> </select>
</div>
