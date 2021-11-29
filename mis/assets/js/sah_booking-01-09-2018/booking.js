$(document).ready(function(){

	$('#room_alloc_button').attr('disabled','disabled').click(function(e){ 
	var build_val = $('select[name=building]').val();
	if(!build_val )
	{
		alert('Please select building');
		return false;
	}
	
	

 	var double_AC_limit = $('input[name="double_AC"]').val();
	var suite_AC_limit = $('input[name="suite_AC"]').val();	
		
	
	var disabled_box_double_bedded_ac=$('[name="checkbox_double_bedded_ac[]"][disabled="disabled"]').length;//alert(disabled_box_double_bedded_ac);//return false;
	var disabled_box_double_ac_suite=$('[name="checkbox_ac_suite[]"][disabled="disabled"]').length;
	

	if($('[name ="checkbox_double_bedded_ac[]"]:not(":checked")').length-disabled_box_double_bedded_ac >0 && double_AC_limit-$('[name ="checkbox_double_bedded_ac[]"]:checked').length>0 || $('[name ="checkbox_ac_suite[]"]:not(":checked")').length-disabled_box_double_ac_suite>0 && suite_AC_limit-$('[name ="checkbox_ac_suite[]"]:checked').length>0)
	{
		alert ('Rooms are still available . Please select .');
		return false;
	}

	if($fla===1)
	{
		$('#room_alloc_button').val("Rooms Unavailable");
	}
	else
	{
		$('#room_alloc_button').val("Submit");
	}
	
		
});
	if($('input[name="room_total"]').val() != 'No room left to be allocated.'){
		$('#room_alloc_button').removeAttr('disabled');
	}


	$('#no_of_guests').change(function(){
		var no_of_guest = parseInt($('#no_of_guests').val());
		if(parseInt(this.value) <= 0){
			alert("Number of Guests cannot be 0 or less!");
			$(this).val(1);
			no_of_guest = 1;
		}
		$("#double_AC option").remove();
		$("#single_AC option").remove();
		$("#suite_AC option").remove();
		for($i=0; $i<= no_of_guest; $i++ ){
			$("#double_AC").append($('<option>',{value: $i, text: $i }));
			$("#single_AC").append($('<option>',{value: $i, text: $i }));
			$("#suite_AC").append($('<option>',{value: $i, text: $i }));
		}
	});
    $('#sum').hide();
    $('#rejection_reason').hide();
    $('#reason').removeAttr("required");
	$('#approve').click(function(){
		$('#rejection_reason').hide();
		$('#reason').removeAttr("required");
		$('#details_submit').show();
		$('#status').val('Approved');
		$('#sum').show();
	});

	$('#reject').click(function(){
		$("#reason").attr("required","required");
		$("#rejection_reason").show();
		$('#details_submit').show();
		$('#status').val('Rejected');
		$('#sum').show();
	});
    
	$('input[name="visitor_category"]').on('ifChanged', function(){
		var value  = this.value;
		document.getElementById("vc").value = value;
		if(value == "Aa"){
			$("#approval_letter").attr("required","required");
			$("#approval_letter_col").show();			
		}
		else{
			$("#approval_letter").removeAttr("required");
	        $("#approval_letter_col").hide();
		}
	});

	$('select[name="building"]').change(function(){
			$.ajax({url : site_url("sah_booking/room_allotment/room_plans/"+$(this).val()+"/"+$('#check_in').data('check_in')+"/"+$('#check_out').data('check_out')),
				success : function (result) {
					$('#floor').html(result);
				}
			});
	});

	$('input[name="lodging_with_food"]').on('ifChanged', function(){
		var value  = this.value;
		if(value == '0'){
			$("#food_radio").removeAttr("required");
			$("#food_col").hide();
		}
		else{
			$("#food_radio").attr("required","required");
			$("#food_col").show();
		}
	});

    $("#lodging_proj").hide();
	$("#lodging_proj").removeAttr("required");
   $('input[name="lodging"]').on('ifChanged', function(){
		var value  = this.value;
		if(value == 'project_coordinator'){
			$("#lodging_proj").attr("required","required");
			$("#lodging_proj").show();
		}
		else{
			$("#lodging_proj").removeAttr("required");
			$("#lodging_proj").hide();
		}
	});

    $("#food_proj").hide();
	$("#food_proj").removeAttr("required");
   $('input[name="food"]').on('ifChanged', function(){
		var value  = this.value;
		if(value == 'project_coordinator'){
			$("#food_proj").attr("required","required");
			$("#food_proj").show();
		}
		else{
			$("#food_proj").removeAttr("required");
			$("#food_proj").hide();
		}
	});

	$('#no_of_guests').change(function(){
		var no_of_guest = parseInt($('#no_of_guests').val());
		if(parseInt(this.value) <= 0){
			alert("Number of Guests cannot be 0 or less!");
			$(this).val(1);
			no_of_guest = 1;
		}
		$("#double_AC option").remove();
		$("#single_AC option").remove();
		$("#suite_AC option").remove();
		for($i=0; $i<= no_of_guest; $i++ ){
			$("#double_AC").append($('<option>',{value: $i, text: $i }));
			$("#single_AC").append($('<option>',{value: $i, text: $i }));
			$("#suite_AC").append($('<option>',{value: $i, text: $i }));
		}
	});
	
	$('select[name="double_AC"]').change(function(){
		var value  = parseInt($('#no_of_guests').val());
		if (value < parseInt($('select[name="suite_AC"]').val()) + parseInt($('select[name="double_AC"]').val()) + parseInt($('select[name="single_AC"]').val())){
			alert("Number of Rooms can't be greater than number of Guests.");
			$('select[name="double_AC"]').val('0');
		}
	});

	$('select[name="single_AC"]').change(function(){
		var value  = parseInt($('#no_of_guests').val());
		if (value < parseInt($('select[name="suite_AC"]').val()) + parseInt($('select[name="double_AC"]').val()) + parseInt($('select[name="single_AC"]').val())){
			alert("Number of Rooms can't be greater than number of Guests.");
			$('select[name="single_AC"]').val('0');
		}
	});

	$('select[name="suite_AC"]').change(function(){
		var value  = parseInt($('#no_of_guests').val());
		if (value < parseInt($('select[name="suite_AC"]').val()) + parseInt($('select[name="double_AC"]').val()) + parseInt($('select[name="single_AC"]').val())){
			alert("Number of Rooms can't be greater than number of Guests.");
			$('select[name="suite_AC"]').val('0');
		}
	});

	

	$('[name="cancel"]').click(function(){
		var reason = prompt("Cancel Reason?");
		if(reason)
			$('[name="cancel_reason"]').val(reason);
		else return false;
	});

});

function checkIfImage(string) {
	if(string.match(/\.(jpeg|jpg|gif|png)/i) != null)
		return true;
	else return false;
}
