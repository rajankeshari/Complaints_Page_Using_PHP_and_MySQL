$(document).ready(function(){
     	    					$(window).load(function() {
     	    					
     	    					
     	    						$.ajax({
											url: site_url("ajax/department/"),
											success: function(result){
												for(i=1;i<=10;i++){
													var id="#e_emp_dept"+i;
												
													$(id).append(result);
												}
													
												
											}
										});
     	    						
									$('#e_emp_dept1').on('change' , function()
									{
     	    							onclick_empname1(1);
     	    						
									});	
									$('#e_emp_dept2').on('change' , function()
									{
     	    							onclick_empname1(2);
     	    						
									});	
									$('#e_emp_dept3').on('change' , function()
									{
     	    							onclick_empname1(3);
     	    						
									});	
									$('#e_emp_dept4').on('change' , function()
									{
     	    							onclick_empname1(4);
     	    						
									});	
									$('#e_emp_dept5').on('change' , function()
									{
     	    							onclick_empname1(5);
     	    						
									});
									$('#e_emp_dept6').on('change' , function()
									{
     	    							onclick_empname1(6);
     	    						
									});
									$('#e_emp_dept7').on('change' , function()
									{
     	    							onclick_empname1(7);
     	    						
									});
									$('#e_emp_dept8').on('change' , function()
									{
     	    							onclick_empname1(8);
     	    						
									});
									$('#e_emp_dept9').on('change' , function()
									{
     	    							onclick_empname1(9);
     	    						
									});
									$('#e_emp_dept10').on('change' , function()
									{
     	    							onclick_empname1(10);
     	    						
									});
									
									$('#e_employee_select1').on('change' , function()
									{

     	    							document.getElementById('e_emp_no1').value=this.value;
     	    							designation1(1);
     	    						
									});	
									$('#e_employee_select2').on('change' , function()
									{

     	    							document.getElementById('e_emp_no2').value=this.value;
     	    							designation1(2);
     	    						
									});	
									$('#e_employee_select3').on('change' , function()
									{

     	    							document.getElementById('e_emp_no3').value=this.value;
     	    							designation1(3);
     	    						
									});	
									$('#e_employee_select4').on('change' , function()
									{

     	    							document.getElementById('e_emp_no4').value=this.value;
     	    							designation1(4);

     	    						
									});	
									$('#e_employee_select5').on('change' , function()
									{

     	    							document.getElementById('e_emp_no5').value=this.value;
     	    							designation1(5);
     	    						
									});
									$('#e_employee_select6').on('change' , function()
									{

     	    							document.getElementById('e_emp_no6').value=this.value;
     	    							designation1(6);
     	    						
									});
									$('#e_employee_select7').on('change' , function()
									{

     	    							document.getElementById('e_emp_no7').value=this.value;
     	    							designation1(7);
     	    						
									});
									$('#e_employee_select8').on('change' , function()
									{

     	    							document.getElementById('e_emp_no8').value=this.value;
     	    							designation1(8);
     	    						
									});
									$('#e_employee_select9').on('change' , function()
									{

     	    							document.getElementById('e_emp_no9').value=this.value;
     	    							designation1(9);
     	    						
									});
									$('#e_employee_select10').on('change' , function()
									{

     	    							document.getElementById('e_emp_no10').value=this.value;
     	    							designation1(10);
     	    						
									});

									
     	    					});	
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
function onclick_empname1(table_id)
	{

		document.getElementById('employee').style.display="inherit";
		var emp_name=document.getElementById('e_employee_select'+table_id);

		var dept=document.getElementById('e_emp_dept'+table_id).value;
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

function designation1(table_id)
{

	var emp_no=document.getElementById('e_employee_select'+table_id).value;
	
	var des_name=document.getElementById('e_des'+table_id);
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
			    des_name.value += xmlhttp.responseText;
		    }
	  	}
		xmlhttp.open("POST",site_url("consultant/consultant/designation/"+emp_no),true);
		xmlhttp.send();
		des_name.innerHTML = "<i class=\"loading\"></i>";
}