$('document').ready(function(){
	$('#outersider_info').hide();
	if($('#assignee_type').val()==="O"){
			$('#insider').hide();
			$('#outersider_info').show();
			$('#assignee_emp_id').val()="";
			$('#assignee_auth_id').val()="";
		}
		else{
			$('#insider').show();
			$('#outersider_info').hide();
	}
	$("#task_assig_date").datepicker({format:'dd-mm-yyyy',autoclose:'true'});
	$('#task_assig_time').timepicker({autoclose:'true',autoclose:'true'});
	$("#task_comp_date").datepicker({format:'dd-mm-yyyy',autoclose:'true'});
	$('#task_comp_time').timepicker({autoclose:'true',autoclose:'true'});
	
	
	$('#assignee_type').change(function(){
		if($('#assignee_type').val()==="O"){
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
	
	
	$( ".select_emp" ).change(function() {
		id = $(this).attr('id');
	//	alert($("#"+id+" option:selected").val());
	   var postdata =  {emp_no:$("#"+id+" option:selected").val(), type:((id=='assignee_emp_id' || id=='appointee_emp_id' )? '2':'1'   )}  ;
    $.ajax({
        url: site_url('appointment/appoint/get_auth_id_method'),
        type: "POST",       
        dataType: "json",        
        data: postdata ,
        success: function (data) {                                                             ;
                if(data.result!='Failed'){                                                                        
                
				   if(id=='assignee_emp_id')
			          $('#assignee_auth_id').val(data.auth_id);
				   else if(id=='assigner_emp_id')
					  $('#assigner_auth_id').val(data.auth_id);
				
			    
                   else if(id=='appointee_emp_id')				
			           $('#appointee_auth_id').val(data.auth_id);
				   else if(id=='appointer_emp_id')		
                        $('#appointer_auth_id').val(data.auth_id);					   
					
					//console.log();
				}
                }                      
           });        
        });        
});

function verify(){
	if($('#assignee_type').val()=="O"){
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
			if($('#assigner_emp_id').val()==""){
				$('#e_assignee_emp_id').html('Assignee Employee is required.');
				return false;
			}
			else{
				return true;
			}
		}
		else{
			if($('#assignee_emp_id').val()==""){
				$('#e_assignee_emp_id').html('Assignee Employee is required.');
			}
			return false;
		}
	}
}

function check_genereal(){
	//alert("Hello");
	var status=true;
	if($('#assigner_emp_id').val()===""){
		$('#e_assigner_emp_id').html('Assigner Employee is required.');
		status=false;
	}
	if($('#task_assig_date').val()===""){
		$('#e_task_assig_date').html('Task Assigned Date is required.');
		status=false;
	}
	if($('#task_assig_time').val()===""){
		$('#e_task_assig_date').html('Task Assigned Time is required.');
		status=false;
	}
	if($('#task_comp_date').val()===""){
		$('#e_task_comp_date').html('Task Due Date is required.');
		status=false;
	}
	if($('#task_comp_time').val()===""){
		$('#e_task_comp_time').html('Task Due Time is required.');
		status=false;
	}
	
	if($('#task_info').val()===""){
		$('#e_task_info').html('Task information is required.');
		status=false;
	}
	
	if($('#domain').val()===""){
		$('#e_domain').html('Domain to  which  task belongs is required.');
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

 






