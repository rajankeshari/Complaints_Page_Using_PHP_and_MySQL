$(document).ready(function(){
	$('#other_testing_type').hide();
	$('#other_client_type').hide();
	$("#currency").on('change', function() {
		if (this.value == '1')
		{

			$('#currency_row').show();
		}
		else
		{
			$('#currency_row').hide();
		}
	});

	$("#testing_type").on('change', function() {
		if (this.value == '10')
		{

			$('#other_testing_type').show();
		}
		else
		{
			$('#other_testing_type').hide();
		}
	});
	$("#client_type").on('change', function() {
		if (this.value == '6')
		{

			$('#other_client_type').show();
		}
		else
		{
			$('#other_client_type').hide();
		}
	});

	$('#form_submit').on('submit', function(e) {
		
			if(!form_validation())
				e.preventDefault();
		});
	$(window).load(function()
	{
		$('#currency_row').hide();
		
	});
});
function form_validation()
	{

		/*var pgv = parent_gaurdian_validation();
		var abov = admission_based_on_validation();
		var cb = course_branch_validation();
		var cav = correspondence_addr_validation();
		var stv = student_type_validation();
		var anv = all_number_validation();
		var iv = image_validation();
		return pgv && abov && cb && cav && stv && iv;
		if(!select_validation())
			return false;
		//else
			//alert('nope');
		if(!password_validation())
			return false;
		if(!parent_guardian_validation())
			return false;
		if(!admission_based_on_validation())
			return false;
		
		if(!student_type_validation())
			return false;*/
		if(!correspondence_addr_validation())
			return false;
		/*if(!all_number_validation())
			return false;
		if(!mobile_number_size_validation())
			return false;
		if(!course_branch_validation())
			return false;
		if(!education_validation())
			return false;
		if(!image_validation())
			return false;
		//push_na_in_empty();
		//return false;*/
		return true;
	}
	function correspondence_addr_validation()
	{
		
			var line1 = document.getElementById("address").value;
			var city = document.getElementById("city").value;
			var pincode = document.getElementById("pincode").value;
			var contact = document.getElementById("client_phone_no").value;
			var fax = document.getElementById("client_fax").value;
			var value = document.getElementById("value_fig").value;
			var value_word = document.getElementById("value_word").value;
			
			var cheque_amt = document.getElementById("dd_cheque_amount").value;
			
			if(line1.trim() == '' ||city.trim() =='' || 
				pincode.trim() == ''|| contact.trim() == '')
			{
				alert("Please fill all the fields of client detail.");
				$('#address').focus();
				return false;
			}
			
			else if(isNaN(pincode))
			{
				
				alert("Pincode can contain only numbers.");
				$('#pincode').focus();
				return false;
			}
			if(isNaN(contact))
			{
				alert("Correspondance Contact can contain only numbers.");
				$('#client_phone_no').focus();
				return false;
			}

			else if(contact >= 10000000000 || contact < 1000000000 || contact.length != 10)
			{
				
				alert("Correspondence phone number not in range.");
				$('#client_phone_no').focus();
				return false;
			}
			if(isNaN(value))
			{
				
				alert("Total value(in fig) can only contain number.");
				$('#value_fig').focus();
				return false;
			}
			if(isNaN(fax))
			{
				
				alert("Fax can only contain number.");
				$('#client_fax').focus();
				return false;
			}
			var letters = /^[A-Z a-z]+$/; 
			
			if(!letters.test(value_word))
			{
				
				alert("Total value(in word) can only contain alphabet.");
				$('#value_word').focus();
				return false;
			}
			if(isNaN(cheque_amt))
			{

				alert("Cheque amount can only contain number.");
				$('#dd_cheque_amount').focus();
				return false;
			}
			return true;
		
	}