function changeplacementslot(session)
{
	console.log(session);
	
	$box = $("#box_portal");
	
	$from = $("#portal_date_from_"+session).val();
	$to = $("#portal_date_to_"+session).val();
	
	if($from == "")
		$("#portal_date_from_"+session).focus();
	else if($to == "")
		$("#portal_date_to_"+session).focus();
	else
	{
		$box.showLoading();
		console.log('reached here'+session+"/"+$from+"/"+$to);
		$.ajax({
			url:site_url("tnpcell/placement_registration/json_update_portal_date/"+session+"/"+$from+"/"+$to),
			success:function(data){
				//alert(data);
				console.log(data);
				$box.hideLoading();
				document.location.href = "placement_registration";
			},
			dataType:"json",
			fail:function(error){
				console.log(error);
				$box.hideLoading();
			}
		});	
	}
}

function AddStudent()
{
	$box = $("#box_add_stu_to_placement");
	$id = $("#add_stu_id").val();
	$remark = $("#add_stu_remark").val();

	if($id == "" || $remark == ""){
		$("#add_stu_id").focus();
	}
	else
	{
		$box.showLoading();
		$.ajax({
			url:site_url("tnpcell/placement_registration/json_add_student"),
			data : {
				stu_id : $id,
				remark : $remark
			},
			success:function(data){
				console.log(data);
				if(data)
					alert($id+" Added Successfully");
				else
					alert("Error in adding student");
				//console.log(data);
				$box.hideLoading();
			},
			type:"post",
			fail:function(error){
				console.log(error);
				$box.hideLoading();
			}
		});	
	}	
}


function RemoveStudent()
{
	$box = $("#box_add_stu_to_placement");
	$id = $("#remove_stu_id").val();
	$remarks = $("#remove_stu_remark").val();

	if($id == "" || $remarks == "")
		$("#remove_stu_id").focus();
	else
	{
		$box.showLoading();
		$.ajax({
			url:site_url("tnpcell/placement_registration/json_remove_student"),
			data : {
				stu_id : $id,
				remark : $remarks
			},
			success:function(data){
				if(data)
					alert($id+" Removed Successfully");
				else
					alert("Error in removing student");
				console.log(data);
				$box.hideLoading();
				$('#stu_info').html('<html> <body></body></html>');
			},
			type:"post",
			fail:function(error){
				console.log(error);
				$box.hideLoading();
				$('#stu_info').html('');
			}
		});	
	}	
}

function get_student_info()
{
	//alert($("#remove_stu_id").val());
	$id = $("#remove_stu_id").val();
	$remarks = $("#remove_stu_remark").val();

	if($id == "" || $remarks == "")
		$("#remove_stu_id").focus();
	else
	{
		$.ajax({
			url:site_url("tnpcell/placement_registration/show_stu_details"),
			data : {
				stu_id : $id,
				remark : $remarks
			},
			success:function(data){

	              $('#stu_info').html(data);
			},
			type:"post",
			fail:function(error){
				console.log(error);
			}
		});	
	}	


}
