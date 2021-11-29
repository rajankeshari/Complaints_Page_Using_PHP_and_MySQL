$(document).ready(function(){
	$("#circular_cat").on('change', function() {
	
		if (this.value == 'ind')
		{

			ind_col();
		}
			
		else if(this.value == 'grp')
		{
			$('#emp_id_col').hide();
			$('#stu_id_col').hide();
			$('#cant_rem_emp_id').hide();
			$('#cant_rem_admission_id').hide();
			$('#individual_col').hide();
			$('#category_col').hide();
			$('#department_col').hide();
			$('#department_col_stu').hide();
			$('#course_col').hide();
			$('#sem').hide();
			$('#type_col').hide();
			$('#groups').hide();
			$('#add_grp_col').show();
		}
		else if(this.value == 'gen')
		{
			
			general_col()
		}
		else
		{
			$('#emp_id_col').hide();
			$('#stu_id_col').hide();
			$('#cant_rem_emp_id').hide();
			$('#cant_rem_admission_id').hide(); 
			$('#individual_col').hide();
			$('#category_col').hide();
			$('#department_col').hide();
			$('#department_col_stu').hide();
			$('#course_col').hide();
			$('#sem').hide();
			$('#type_col').hide();
			$('#groups').hide();
			$('#add_grp_col').hide();
		}
				
	
	});
	$(window).load(function()
	{

			$('#emp_id_col').hide();
			$('#stu_id_col').hide();
			$('#cant_rem_emp_id').hide();
			$('#cant_rem_admission_id').hide();
			$('#individual_col').hide();
			$('#category_col').hide();
			$('#department_col').hide();
			$('#department_col_stu').hide();
			$('#course_col').hide();
			$('#sem').hide();
			$('#type_col').hide();
			$('#groups').hide();
			$('#group').hide();
			$('#add_grp_col').hide();
			$('#type_crt_grp').hide();
	});
});
function ind_col()
{
	$('#emp_id_col').hide();
			$('#stu_id_col').hide();
			$('#individual_col').show();
			$('#category_col').hide();
			$('#department_col').hide();
			$('#department_col_stu').hide();
			$('#course_col').hide();
			$('#sem').hide();
			$('#type_col').hide();
			$('#groups').hide();
			$('#add_grp_col').hide();
			$('#cant_rem_emp_id').hide();
			$('#cant_rem_admission_id').hide();
			$('#employee').hide();
				$('#search_eid').hide();
				$('#courses_sid').hide();
				$('#search_sid').hide();
				$('#emp_id_col').hide();
				$('#stu_id_col').hide();
				$('#students').hide();
				$('#employee').hide();
				$("#no_of_individuals").val('');
			$("#no_of_individuals").on('keyup' , function()
			{
				
				no_of_individuals(this.value);

			});
}
function general_col()
{
			$('#emp_id_col').hide();
			$('#stu_id_col').hide();
			$('#cant_rem_emp_id').hide();
			$('#cant_rem_admission_id').hide();
			$('#individual_col').hide();
			$('#category_col').show();
			$('#department_col').show();
			$('#department_col_stu').hide();
			$('#course_col').hide();
			$('#sem').hide();
			$('#type_col').hide();
			$('#groups').hide();
			$('#add_grp_col').hide();
			$('#cat_selection').val('all');
			$('#cat_selection').on('change' , function()
			{
					if(this.value == 'all')
					{
							$('#course_col').hide();
							$('#type_col').hide();
							$('#department_col_stu').hide();
							$('#department_col').show();
							$('#dept_selection').val('all');
							$('#sem').hide();
					}
					else if(this.value == 'emp')
					{
							$('#department_col').show();
							$('#dept_selection').val('all');
							$('#department_col_stu').hide();
							$('#course_col').hide();
							$('#type_col').show();
							$('#emp_selection1').val('all');
							$('#sem').hide();
					}
					else if(this.value=='stu')
					{
						$('#type_col').hide();
						$('#department_col').hide();
						$('#sem').hide();
						$('#department_col_stu').show();
						$('#dept_selection_stu').val('all');
						get_courses('all');
						$('#dept_selection_stu').on('change',function()
						{
							$('#sem').hide();
							get_courses(this.value);
						});
						$('#course_col').show();
						
						$("#course_selection1").on('change' , function()
						{
							if(this.value!='all')
							{
								$('#sem').show();
								semester(this.value);
							}
							
							else
							$('#sem').hide();
						});
					}
					
			});
}
function no_of_individuals(people)
{
	if (people > 0)
				{
					
					$('#groups').show();
					index(people);
					$('#cant_rem_emp_id').show();
					$('#cant_rem_admission_id').show();
					$("#employee_select").on('change' , function()
					{
						$('#emp_id_col').show();
						$('#stu_id_col').hide();

					});
					$("#student_select").on('change' , function()
					{
						$('#stu_id_col').show();
						$('#emp_id_col').hide();
					});

				}
				else
				{

					$('#emp_id_col').hide();
					$('#groups').hide();
					$('#cant_rem_emp_id').hide();
					$('#cant_rem_admission_id').hide();
				}
}
function onclick_emp_id()
	{
		document.getElementById('search_eid').style.display="inherit";
		//changes
		$('#stu_id_col').hide();
		$('#students').hide();
		$('#search_sid').hide();
		$('#courses_sid').hide();
		$('#search_eid').show();
		
		var dept=document.getElementById('emp_dept');
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
			    dept.innerHTML = xmlhttp.responseText;
		    }
	  	}

		xmlhttp.open("POST",site_url("information/information_ajax/get_dept"),true);
		xmlhttp.send();

	}
function onclick_stu_id()
	{
		document.getElementById('search_sid').style.display="inherit";
		//changes
		$('#emp_id_col').hide();
		$('#employee').hide();
		$('#search_eid').hide();
		$('#courses_sid').hide();
		$('#search_sid').show();
		var dept=document.getElementById('stu_dept');
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
			    dept.innerHTML = xmlhttp.responseText;
		    }
	  	}

		xmlhttp.open("POST",site_url("information/information_ajax/get_depts"),true);
		xmlhttp.send();

	}
	function onclick_stucourse()
	{

		document.getElementById('courses_sid').style.display="inherit";
		//changes
		$('#stu_id_col').hide();
		$('#students').hide();
		
		
		var dept=document.getElementById('stu_dept').value;
		var course=document.getElementById('stu_course');
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
			    course.innerHTML = xmlhttp.responseText;
		    }
	  	}

		xmlhttp.open("POST",site_url("information/information_ajax/get_courses/"+dept),true);
		xmlhttp.send();

	}
	function onclick_empname()
	{
		document.getElementById('employee').style.display="inherit";
		$('#emp_id_col').hide();
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

	function onclick_stuname()
	{
		document.getElementById('students').style.display="inherit";
		$('#stu_id_col').hide();
		var stu_name=document.getElementById('student_select');
		var dept=document.getElementById('stu_dept').value;
		var course=document.getElementById('stu_course').value;
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
			    stu_name.innerHTML += xmlhttp.responseText;
		    }
	  	}
		xmlhttp.open("POST",site_url("ajax/stuNameByDeptAndCourse/"+dept+"/"+course),true);
		xmlhttp.send();
		stu_name.innerHTML = "<i class=\"loading\"></i>";
	}
	function onclick_emp_nameid()
	{
		var emp_name_id=document.getElementById('employee_select').value;
		document.getElementById('emp_id').value=emp_name_id;
	}
	function onclick_stu_nameid()
	{
		var stu_name_id=document.getElementById('student_select').value;
		document.getElementById('stu_id').value=stu_name_id;
	}

	function onload_emp_id()
	{
		var emp=document.getElementById('emp_id');
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
			    emp.innerHTML += xmlhttp.responseText;
		    }
	  	}
		xmlhttp.open("POST",site_url("information/information_ajax/get_emp_id"),true);
		xmlhttp.send();
	}
	function onload_stu_id()
	{
		var stu=document.getElementById('stu_id');
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
			    stu.innerHTML += xmlhttp.responseText;
		    }
	  	}
		xmlhttp.open("POST",site_url("information/information_ajax/get_stu_id"),true);
		xmlhttp.send();
	}

	
	
