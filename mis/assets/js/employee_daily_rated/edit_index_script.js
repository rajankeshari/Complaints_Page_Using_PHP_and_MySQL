	$(document).ready(function() {
		$("#search_btn").click(function(){
			$("#search_eid").show();
		});
		
		$('#form').on('submit', function(e) {
                       if(!validation())
                                e.preventDefault();
              });
		
		$("#emp_dept").on('change', onclick_empname);
		$("#employee_select").on('change',onclick_emp_nameid);
				
	});
	
	function validation()
	{
		if(document.getElementById('emp_id').value == "0")
					{
						alert("Please select an employee");
						return false;
					}
			return true
	}
	
	function onclick_empname()
	{
		$('#employee').show();
		var emp_name=document.getElementById('employee_select');
		var dept=document.getElementById('emp_dept').value;
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
		xmlhttp.open("POST",site_url("employee_daily_rated/edit/empNameByDept/"+dept),true);
		xmlhttp.send();
		emp_name.innerHTML = "<i class=\"loading\"></i>";
	}

	function onclick_emp_nameid() {
		var emp_name_id=document.getElementById('employee_select').value;
		document.getElementById('emp_id').value=emp_name_id;
	}