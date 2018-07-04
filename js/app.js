
$(document).ready(function(){
	$('#data_set').hide();
	$('#data_oem').hide();
	get_dataSet();

	get_dataSetOEM();
	$.ajax({
				type: "POST",
				url: "ajax/",
				dataType: 'json',
				data: {
				action:'getOEM'
			},
	success: function(data){
		var html='';
		html +="<option value='-1'>Select OEM</option>";
		for(var j=0;j<data.value.length;j++)
		{
			html +="<option value="+data.value[j]['oem']+">"+data.value[j]['oem']+"</option>";
		}

		if(data.value.length==0)
			html+="<option> No Data</option>";
		
		$("#oem_val").html(html);
		$('#data_oem').show();
		}
	});


$('#oem_val').on('change',function(){
		var oem_val = $('#oem_val').val();
		console.log(oem_val);
	  $.ajax({
		type: "POST",
		url: "ajax/",
		dataType: 'json',
		data: {
		action:'getOEM_Model',
		oem:oem_val,
	},
	success: function(data){
		console.log(data);
		var html = "<table class='table table-striped table-bordered' id='example'> ";
		html +="<thead><tr><th>S.NO</th><th>OEM</th><th>Head Unit Type</th><th>Count</th></tr></thead><tbody>";
		for(var j=0;j<data.value.length;j++)
		{
			let sno=j+1;
			html +="<tr><td>"+sno+"</td>";
			html +="<td>"+data.value[j]['oem']+"</td><td>"+data.value[j]['head_unit_type']+"</td><td>"+data.value[j]['cnt']+"</td>";
		}

		if(data.error.length==0)
			html+="<tr><th colspan='4' style='text-align:center;'>No Data Found .!</th></tr>"
		html +="</tbody></table>";


		$("#data_oem_model").html(html);
		$('#data_oem_model').show();
		}
	  });
	});
	
});


function get_dataSet(){
	$.ajax({
				type: "POST",
				url: "ajax/",
				dataType: 'json',
				data: {
				action:'getall'
			},
	success: function(data){
		var table = "<table class='table table-striped table-bordered' id='example'> ";
		table +="<thead><tr><th>S.NO</th><th>OEM</th><th>Model</th><th>Segment</th><th>Variant</th></tr></thead><tbody>";
		for(var j=0;j<data.value.length;j++)
		{
			let sno=j+1;
			table +="<tr><td>"+sno+"</td>";
			
			table +="<td id='country_"+data.value[j]['oem']+"'>"+data.value[j]['oem']+"</td><td>"+data.value[j]['model']+"</td><td>"+data.value[j]['variant']+"</td>";
			
			table +="<td>"+data.value[j]['segment']+"</td></tr>";
		}

		if(data.value.length==0)
			table+="<tr><th colspan='7' style='text-align:center;'>No Data Found .!</th></tr>"
		table +="</tbody><tfoot><tr><th>S.NO</th><th>OEM</th><th>Model</th><th>Segment</th><th>Variant</th></tr></tfoot></table>";

		$("#data_set").append(table);
		$('#example').DataTable();
		$('#data_set').show();
		}
	});
}


function get_dataSetOEM(){
	$.ajax({
				type: "POST",
				url: "ajax/",
				dataType: 'json',
				data: {
				action:'getall'
			},
	success: function(data){
		var table = "<table class='table table-striped table-bordered' id='oem_model'> ";
		table +="<thead><tr><th>OEM</th><th>Model</th><th>Standard</th><th>Stand Alone</th><th>Package</th><th>Proxy</th><th>Car Play</th><th>Andriod Auto</th></tr></thead><tbody>";
		for(var j=0;j<data.value.length;j++)
		{
			table +="<td>"+data.value[j]['oem']+"</td><td>"+data.value[j]['model']+"</td><td>"+data.value[j]['standard']+"</td>";
			
			table +="<td>"+data.value[j]['stand_alone']+"</td><td>"+data.value[j]['pack']+"</td><td>"+data.value[j]['proxy']+"</td><td>"+data.value[j]['carplay']+"</td><td>"+data.value[j]['andriod_auto']+"</td></tr>";
		}

		if(data.value.length==0)
			table+="<tr><th colspan='8' style='text-align:center;'>No Data Found .!</th></tr>"
		table +="</tbody><tfoot><tr><th>OEM</th><th>Model</th><th>Standard</th><th>Stand Alone</th><th>Package</th><th>Proxy</th><th>Car Play</th><th>Andriod Auto</th></tr></tfoot></table>";

		$("#oem_model_data").append(table);
		 $('#oem_model').DataTable( {
	        initComplete: function () {
	            this.api().columns().every( function () {
	                var column = this;
	                var select = $('<select><option value=""></option></select>')
	                    .appendTo( $(column.footer()).empty() )
	                    .on( 'change', function () {
	                        var val = $.fn.dataTable.util.escapeRegex(
	                            $(this).val()
	                        );
	 
	                        column
	                            .search( val ? '^'+val+'$' : '', true, false )
	                            .draw();
	                    } );
	 
	                column.data().unique().sort().each( function ( d, j ) {
	                    select.append( '<option value="'+d+'">'+d+'</option>' )
	                } );
	            } );
	        }
	    } );
		 $('#oem_model tfoot tr').insertAfter($('#oem_model thead tr'));
		$('#oem_model_data').show();
		}
	});
}