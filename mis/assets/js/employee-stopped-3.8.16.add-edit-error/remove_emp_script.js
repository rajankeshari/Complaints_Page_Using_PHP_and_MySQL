$(document).ready(function() {
	$cont_table = $('#view_emp');
	$('#emp_no').hide();
	$('#removalDate').hide();
	$('#empCont').hide();
	//$('#deptCont').hide();
	$('#buttonCont').hide();
	$('.emp_details').hide();

	$('#perSubmit').on('submit', function(e) {
                        if(!emp_validation())
                          e.preventDefault();
                });
				
	$('#tempSubmit').on('submit', function(e) {
                        if(!t_emp_validation())
                          e.preventDefault();
                });
	
	$('#reason').on('change', function() {

		$reason_selection = $('#reason');
		
		if($reason_selection.val()=='retired')
		{
			$('#emp_no').hide();
			$('#removalDate').hide();
			$('#empCont').hide();
			$('#deptCont').hide();
			$('#buttonCont').hide();
			$('.emp_details').hide();
			retired_emp_data();
		}
		if($reason_selection.val()=='expired' || $reason_selection.val()=='resigned' || $reason_selection.val()=='terminated')
		{
			$('#empCont').show();
			$('#deptCont').hide();
			$('#emp_no').show();
			$('#removalDate').show();
			$('#buttonCont').show();
			$('#p_emp_details').show();
		}
		/*if($reason_selection.val()=='transfered')
		{
			$('#empCont').show();
			$('#deptCont').show();
			$('#emp_no').show();
			$('#removalDate').show();
			$('#desig').show();
			$('#name').show();
			$('#buttonCont').show();
			$('#p_emp_details').show();
		}*/
	})
	
	$('#emp_no').on('change', function() {
			var emp_no = $('#emp_no').val();
			$('#p_emp_details').show();
			get_p_emp_details(emp_no);
		});
	
	$('#t_emp_no').on('change', function() {
			var emp_no = $('#t_emp_no').val();
			$('#t_emp_details').show();
			get_t_emp_details(emp_no);
		});
	
	
	function retired_emp_data() {
		$.ajax({url : site_url("employee/remove/get_retired_emp"),
				success : function (result) {
					$cont_table.html(result);
				}});
	}
	
	function get_p_emp_details(emp_no) {
		$.ajax({url : site_url("employee/remove/show_emp_details/"+emp_no),
				success : function (result) {
					$('#p_emp_details').html(result);
				}});
	}
	
	function get_t_emp_details(emp_no) {
		$.ajax({url : site_url("employee/remove/show_emp_details/"+emp_no),
				success : function (result) {
					$('#t_emp_details').html(result);
				}});
	
	}
	
	function emp_validation() {
	var empno=document.getElementById("emp_no").value;
	if( empno == '0')
		{
			alert("Please select an employee number.");
			return false;
		}
		else
		return true;
	}
	
	function t_emp_validation() {
	var empno=document.getElementById("t_emp_no").value;
	if( empno == '0')
		{
			alert("Please select an employee number.");
			return false;
		}
		else
		return true;
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
		xmlhttp.open("POST",site_url("ajax/empNameByDept/"+dept),true);
		xmlhttp.send();
		emp_name.innerHTML = "<i class=\"loading\"></i>";
	}

	function onclick_emp_nameid() {
		var emp_name_id=document.getElementById('employee_select').value;
		document.getElementById('emp_no').value=emp_name_id;
	}
	
	$(document).ready(function() {
		$("#search_btn").click(function(){
			$("#search_eid").show();
		});
		$("#emp_dept").on('change', onclick_empname);
		$("#employee_select").on('change',onclick_emp_nameid);
	});
	//for temporary emp
	function onclick_t_empname()
	{
		$('#t_employee').show();
		var t_emp_name=document.getElementById('t_employee_select');
		var t_dept=document.getElementById('t_emp_dept').value;
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
			    t_emp_name.innerHTML += xmlhttp.responseText;
		    }
	  	}
		xmlhttp.open("POST",site_url("ajax/empNameByDept/"+t_dept),true);
		xmlhttp.send();
		t_emp_name.innerHTML = "<i class=\"loading\"></i>";
	}

	function onclick_t_emp_nameid() {
		var t_emp_name_id=document.getElementById('t_employee_select').value;
		document.getElementById('t_emp_no').value=t_emp_name_id;
	}
	
	$(document).ready(function() {
		$("#t_search_btn").click(function(){
			$("#t_search_eid").show();
		});
		$("#t_emp_dept").on('change', onclick_t_empname);
		$("#t_employee_select").on('change',onclick_t_emp_nameid);
	});
});