
$(document).ready(function(){
     	    					
     	    					
     	    					
     	  
									
     	    					});	
  
/*$(document).ready(function(){
	$("#no_of_persons").on('keyup' , function()
			{
				
				no_of_persons(this.value);

			});
});
function no_of_persons(num)
{
	$.ajax({
		url: site_url("consultant/consultant/detail_table/"+num),
		success: function(result){
			$("#detail_table").html(result);
		}
	});
}*/
function onclick_empname(table_id)
	{

		document.getElementById('employee').style.display="inherit";
		var emp_name=document.getElementById('employee_select'+table_id);

		var dept=document.getElementById('emp_dept'+table_id).value;
		var xmlhttp;
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
		 	xmlhttp=new XMLHttpRequest();
		}
		else
	  	{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
	  	{
	  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		    {
			    emp_name.innerHTML += xmlhttp.responseText;
		    }
	  	}
		xmlhttp.open("POST",site_url("ajax/empNameByDept/"+dept),true);
		xmlhttp.send();
		emp_name.innerHTML = "<i class=\"loading\"></i>";
	}

function designation(table_id)
{

	var emp_no=document.getElementById('employee_select'+table_id).value;
	
	var des_name=document.getElementById('des'+table_id);
	var xmlhttp;
	if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
		 	xmlhttp=new XMLHttpRequest();
		}
	else
	  	{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	xmlhttp.onreadystatechange=function()
	  	{
	  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		    {
		    	
			    des_name.value = xmlhttp.responseText;
		    }
	  	}
		xmlhttp.open("POST",site_url("consultant/consultant/designation/"+emp_no),true);
		xmlhttp.send();
		des_name.innerHTML = "<i class=\"loading\"></i>";
}