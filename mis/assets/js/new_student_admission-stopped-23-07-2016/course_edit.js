$(document).ready(function() {
	
// Edit course	
	
	$dept_selection = $('#depts');
	$course_selection = $('#course');
	$branch_selection = $('#branch');
	
	$cont_course_selection = $('#cont_course_selection');
	$cont_branch_selection = $('#cont_branch_selection');
	$cont_session_selection = $("#cont_session_selection");
	
	$course_selection.hide();
	$branch_selection.hide();
	$cont_course_selection.hide();
	$cont_branch_selection.hide();

           	

                $('#depts').on('change', function() {
					$cont_course_selection.show();
					$course_selection.show();
                        //if($('#stu_type').val() != 'jrf')
                                options_of_courses();
                });

                $('#course').on('change', function() {
					$cont_branch_selection.show();
					$branch_selection.show();
                        
                                options_of_branches();
                });
				
				
				 $('#update_detail').on('submit', function(event) {
                        if(!form_validation())
						event.preventDefault();
                                
                });
				 
				 
				 
				 function form_validation()
		         {
                    var department = document.getElementById("depts").value;
			        if(department == 'select')
				    {
					   alert("Please Select Department.");
                                return false;
				    }
					
					var course = document.getElementById("course").value;
					var student_type = document.getElementById("student_type").value;
					
					
					if(student_type == 'select')
				    {
					   alert("Please Select Student Type.");
                                return false;
				    }
					
					
			        if(course == 'select')
				    {
					    if(student_type!='jrf'&&student_type!='pdf')
						{
					      alert("Please Select Course.");
                                return false;
						}
				    }
					
					var branch = document.getElementById("branch").value;
			        if(branch == 'select')
				    {
						if(student_type!='jrf'&&student_type!='pdf')
					   {
					      alert("Please Select Branch.");
                                return false;
					   }
				    }
					
					
					var adm_based = document.getElementById("admission_based_on").value;
			        if(adm_based == 'select')
				    {
					   alert("Please Select Admission Based On.");
                                return false;
				    }
					
					return true;
		         }
				

                
    function options_of_courses()
    {

		$.ajax({url : site_url("new_student_admission/course_edit_jee/get_courses?p="+$('#depts').val()),
				success : function (result) {
					$('#course').html(result);
				}});
    }
	
	function options_of_branches() 
	{
		$.ajax({url : site_url("new_student_admission/course_edit_jee/get_branches?p="+$('#depts').val()+"&q="+$('#course').val()),
				success : function (result) {
					$('#branch').html(result);
				}});	
	}
	
	
	
	
	// Add Course
	
	$dept1_selection = $('#depts1');
	$course1_selection = $('#course1');
	$branch1_selection = $('#branch1');
	
	$cont1_course_selection = $('#cont1_course_selection');
	$cont1_branch_selection = $('#cont1_branch_selection');
	$cont1_session_selection = $("#cont1_session_selection");
	
	$course1_selection.hide();
	$branch1_selection.hide();
	$cont1_course_selection.hide();
	$cont1_branch_selection.hide();

           	

                $('#depts1').on('change', function() {
					$cont1_course_selection.show();
					$course1_selection.show();
                        //if($('#stu_type').val() != 'jrf')
                                options_of_courses1();
                });

                $('#course1').on('change', function() {
					$cont1_branch_selection.show();
					$branch1_selection.show();
                        
                                options_of_branches1();
                });
				
				
				 $('#add_detail').on('submit', function(event) {
                        if(!form_validation1())
						event.preventDefault();
                                
                });
				 
				 
				 
				 function form_validation1()
		         {
                    var department1 = document.getElementById("depts1").value;
			        if(department1 == 'select')
				    {
					   alert("Please Select Department.");
                                return false;
				    }
					
					var course1 = document.getElementById("course1").value;
					var student_type1 = document.getElementById("student_type1").value;
					
					
					if(student_type1 == 'select')
				    {
					   alert("Please Select Student Type.");
                                return false;
				    }
					
					
			        if(course1 == 'select')
				    {
					    if(student_type1!='jrf'&&student_type1!='pdf')
						{
					      alert("Please Select Course.");
                                return false;
						}
				    }
					
					var branch1 = document.getElementById("branch1").value;
			        if(branch1 == 'select')
				    {
						if(student_type1!='jrf'&&student_type1!='pdf')
					   {
					      alert("Please Select Branch.");
                                return false;
					   }
				    }
					
					
					var adm_based1 = document.getElementById("admission_based_on1").value;
			        if(adm_based1 == 'select')
				    {
					   alert("Please Select Admission Based On.");
                                return false;
				    }
					
					return true;
		         }
				

                
    function options_of_courses1()
    {

		$.ajax({url : site_url("new_student_admission/course_edit_jee/get_courses?p="+$('#depts1').val()),
				success : function (result) {
					$('#course1').html(result);
				}});
    }
	
	function options_of_branches1() 
	{
		$.ajax({url : site_url("new_student_admission/course_edit_jee/get_branches?p="+$('#depts1').val()+"&q="+$('#course1').val()),
				success : function (result) {
					$('#branch1').html(result);
				}});	
	}
	

       
		
});