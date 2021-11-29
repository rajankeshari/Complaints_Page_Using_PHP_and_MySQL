function changeportaldate(company_id)
{
	$box = $("#box_reg_portal");
	$from = $("#portal_date_from_"+company_id).val();
	$to = $("#portal_date_to_"+company_id).val();
	
	if($from == "")
		$("#portal_date_from_"+company_id).focus();
	else if($to == "")
		$("#portal_date_to_"+company_id).focus();
	else
	{
		$box.showLoading();
		$.ajax({
			url:site_url("tnpcell/manage_portal/json_update_portal_date/"+company_id+"/"+$from+"/"+$to),
			success:function(data){
				console.log(data);
				$box.hideLoading();
				//document.location.href = "manage_portal";
			},
			type:"post",
			dataType:"json",
			fail:function(error){
				console.log(error);
				$box.hideLoading();
			}
		});	
	}
}

function RegisterForCompany(company_id,stu_id)
{
	$box = $("#box_reg_portal");
	console.log(stu_id);
	if($("#stu_reg_"+company_id).is(':checked'))
		$register = "1";
	else
		$register = "0";
	
	$box.showLoading();
	$.ajax({
		url:site_url("tnpcell/stu_registration/UpdateCompanyRegistration/"+company_id+"/"+$register),
		success:function(data){
			$box.hideLoading();
			alert("Changes Saved Successfully");
			//console.log(data);
			//document.location.href = "stu_registration/RegistrationforCompany";
		},
		type:"post",
		dataType:"json",
		fail:function(error){
			console.log(error);
			$box.hideLoading();
		}
	});		
}

