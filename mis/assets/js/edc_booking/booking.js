$(document).ready(function(){

	$('#room_alloc_button').attr('disabled','disabled').click(function(e){ 
	var build_val = $('select[name=building]').val();
	if(!build_val )
	{
		alert('Please select building');
		return false;
	}
	//$('[name="building"]').on('change',function(){
	

 	var double_AC_limit = $('input[name="double_AC"]').val();
	var suite_AC_limit = $('input[name="suite_AC"]').val();	
		
	//alert('hello');
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

	$("#approval_letter_col").hide();
	$("#approval_letter").removeAttr("required");
	$('#details_submit').hide();
	$("#rejection_reason").hide();
	$('#no_of_guests').val(1);


	$('input[name="school_guest"]').on('ifChanged', function(){
		var value  = this.value;
		if(value == '0'){
			$("#approval_letter").removeAttr("required");
			$("#approval_letter_col").hide();
		}
		else{
			$("#approval_letter").attr("required","required");
			$("#approval_letter_col").show();
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
		$("#suite_AC option").remove();
		for($i=0; $i<= no_of_guest; $i++ ){
			$("#double_AC").append($('<option>',{value: $i, text: $i }));
			$("#suite_AC").append($('<option>',{value: $i, text: $i }));
		}
	});

	$('#approve').click(function(){
		$('#rejection_reason').hide();
		$('#reason').removeAttr("required");
		$('#details_submit').show();
		$('#status').val('Approved');
	});

	$('#reject').click(function(){
		$("#reason").attr("required","required");
		$("#rejection_reason").show();
		$('#details_submit').show();
		$('#status').val('Rejected');
	});

	$('select[name="purpose"]').change(function(){
		var value  = this.value;
		if(value == 'Personal'){
			$("#approval_letter_col").hide();
			$("#approval_letter").removeAttr("required");
			$("#school_guest_row").hide();
		}
		else{
			$("#approval_letter").attr("required","required");
			$("#school_guest_row").show();
		}
	});

	$('select[name="double_AC"]').change(function(){
		var value  = parseInt($('#no_of_guests').val());
		if (value < parseInt($('select[name="suite_AC"]').val()) + parseInt($('select[name="double_AC"]').val())){
			alert("Number of Rooms can't be greater than number of Guests.");
			$('select[name="double_AC"]').val('0');
		}
	});

	$('select[name="suite_AC"]').change(function(){
		var value  = parseInt($('#no_of_guests').val());
		if (value < parseInt($('select[name="suite_AC"]').val()) + parseInt($('select[name="double_AC"]').val())){
			alert("Number of Rooms can't be greater than number of Guests.");
			$('select[name="suite_AC"]').val('0');
		}
	});

	$('#booking_form').click(function() {
		var checkin = $('#checkin').val();
		var checkout = $('#checkout').val();
		var total_room = parseInt($('#double_AC').val()) + parseInt($('#suite_AC').val());
		var no_of_guests = parseInt($('select[name="no_of_guests"]').val());

		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		if(dd<10) {
		    dd='0'+dd
		}
		if(mm<10) {
		    mm='0'+mm
		}
		today = yyyy+'-'+mm+'-'+dd;
		if(checkin && checkout) {
			if(checkin < today) {
				alert("Checkin Date cannot be less than today!");
				$(this).val('');
				return false;
			}
			else if (checkin>checkout) {
				alert ("Check Out Date can't be earlier than Check In Date.");
				return false;
			}
		}
		if (no_of_guests <= 0){
			alert('Number of Guests cannot be 0!');
			$('select[name="no_of_guests"]').val('1');
			return false;
		}
		if(total_room <= 0){
			alert('Total no of rooms cannot be 0!');
			return false;
		}
		if($('#approval_letter').val() && !checkIfImage($('#approval_letter').val())) {
			$('#approval_letter').focus().val('');
			return false;
		}
	});

	$('select[name="building"]').change(function(){
			$.ajax({url : site_url("edc_booking/room_allotment/room_plans/"+$(this).val()+"/"+$('#check_in').data('check_in')+"/"+$('#check_out').data('check_out')),
				success : function (result) {
					$('#floor').html(result);
				}
			});
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
