$(document).ready(function(){
	$("#editbutton").show();	
	$("#savebutton").hide();	
});

function EditSubject(seq_no,status)
{
	
	if(status==0)
	{
		//alert(seq_no);
		
	jQuery('#editbutton_'+seq_no).hide(function(){
			jQuery('#savebutton_'+seq_no).show();
	});	
		
	$(".count"+seq_no).prop("disabled",false);
	$(".remark"+seq_no).prop("disabled",false);
	}
	else
		alert("You Dont Have Permission To Edit Beacause You Have Submitted The list To DSW")

}