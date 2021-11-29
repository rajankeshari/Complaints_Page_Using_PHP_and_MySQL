$('document').ready(function(){
	$('#outersider_info').hide();
	if($('#appointee_type').val()==="O"){
			$('#insider').hide();
			$('#outersider_info').show();
		}
		else{
			$('#insider').show();
			$('#outersider_info').hide();
	}
	$("#appoint_date").datepicker({format:'dd-mm-yyyy',autoclose:'true'});
	$('#appoint_time').timepicker({autoclose:'true',autoclose:'true'});
	$('#appointee_type').change(function(){
		if($('#appointee_type').val()==="O"){
			$('#insider').hide();
			$('#outersider_info').show();
		}
		else{
			$('#insider').show();
			$('#outersider_info').hide();
		}
	});

	$('#btnSubmit').click(function(){
		if(verify()){
			$('#frm').submit();
		}
		else{
			return false;
		}
	});
	
	setTimeout(function () {
            if ($(".alert").is(":visible")){
                 //you may add animate.css class for fancy fadeout
                $(".alert").fadeOut("fast");
            }
    }, 5000);
});

function verify(){
	if($('#appointee_type').val()=="O"){
		if(check_genereal()){
			if(check_outsider()){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			
			return false;
		}
	}
	else{
		if(check_genereal()){
			if($('#appointee_emp_id').val()==""){
				$('#e_appointee_emp_id').html('Appointee Employee No. is required.');
				return false;
			}
			else{
				return true;
			}
		}
		else{
			if($('#appointee_emp_id').val()==""){
				$('#e_appointee_emp_id').html('Appointee Employee No. is required.');
			}
			return false;
		}
	}
}

function check_genereal(){
	//alert("Hello");
	var status=true;
	if($('#appointer_emp_id').val()===""){
		$('#e_appointer_emp_id').html('Appointer Employee Id is required.');
		status=false;
	}
	if($('#appoint_date').val()===""){
		$('#e_appoint_date').html('Appointment Date is required.');
		status=false;
	}
	if($('#appoint_time').val()===""){
		$('#e_appoint_time').html('Appointment Time is required.');
		status=false;
	}
	return status;

}

function check_outsider(){
	var status=true;
	if($('#name').val()===""){
		status=false;
		$('#e_name').html("Name is required");
	}
	if($('#contact').val()===""){
		status=false;
		$('#e_contact').html("Contact No. is required");
	}
	return status;
}