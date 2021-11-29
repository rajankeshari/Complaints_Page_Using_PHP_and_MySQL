$(document).ready(function(){
     	    					$(window).load(function() {
     	    					
     	    					
     	    						$.ajax({
											url: site_url("ajax/department/"),
											success: function(result){
												for(i=2;i<=10;i++){
													var id="#emp_dept"+i;
												
													$(id).append(result);
												}
													
												
											}
										});
     	    						
									$('#emp_dept2').on('change' , function()
									{
     	    							onclick_empname(2);
     	    						
									});	
									$('#emp_dept3').on('change' , function()
									{
     	    							onclick_empname(3);
     	    						
									});	
									$('#emp_dept4').on('change' , function()
									{
     	    							onclick_empname(4);
     	    						
									});	
									$('#emp_dept5').on('change' , function()
									{
     	    							onclick_empname(5);
     	    						
									});
									$('#emp_dept6').on('change' , function()
									{
     	    							onclick_empname(6);
     	    						
									});
									$('#emp_dept7').on('change' , function()
									{
     	    							onclick_empname(7);
     	    						
									});
									$('#emp_dept8').on('change' , function()
									{
     	    							onclick_empname(8);
     	    						
									});
									$('#emp_dept9').on('change' , function()
									{
     	    							onclick_empname(9);
     	    						
									});
									$('#emp_dept10').on('change' , function()
									{
     	    							onclick_empname(10);
     	    						
									});
									
									$('#employee_select2').on('change' , function()
									{

     	    							document.getElementById('emp_no2').value=this.value;
     	    							designation(2);
     	    						
									});	
									$('#employee_select3').on('change' , function()
									{

     	    							document.getElementById('emp_no3').value=this.value;
     	    							designation(3);
     	    						
									});	
									$('#employee_select4').on('change' , function()
									{

     	    							document.getElementById('emp_no4').value=this.value;
     	    							designation(4);

     	    						
									});	
									$('#employee_select5').on('change' , function()
									{

     	    							document.getElementById('emp_no5').value=this.value;
     	    							designation(5);
     	    						
									});
									$('#employee_select6').on('change' , function()
									{

     	    							document.getElementById('emp_no6').value=this.value;
     	    							designation(6);
     	    						
									});
									$('#employee_select7').on('change' , function()
									{

     	    							document.getElementById('emp_no7').value=this.value;
     	    							designation(7);
     	    						
									});
									$('#employee_select8').on('change' , function()
									{

     	    							document.getElementById('emp_no8').value=this.value;
     	    							designation(8);
     	    						
									});
									$('#employee_select9').on('change' , function()
									{

     	    							document.getElementById('emp_no9').value=this.value;
     	    							designation(9);
     	    						
									});
									$('#employee_select10').on('change' , function()
									{

     	    							document.getElementById('emp_no10').value=this.value;
     	    							designation(10);
     	    						
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
			    des_name.value += xmlhttp.responseText;
		    }
	  	}
		xmlhttp.open("POST",site_url("consultant/consultant/designation/"+emp_no),true);
		xmlhttp.send();
		des_name.innerHTML = "<i class=\"loading\"></i>";
}