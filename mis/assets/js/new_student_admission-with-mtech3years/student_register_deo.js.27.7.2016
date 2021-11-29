$(document).ready(function() {
	
	$dept_selection = $('#depts');
	$course_selection = $('#course_id');
	$branch_selection = $('#branch_id');
	
	$cont_course_selection = $('#cont_course_selection');
	$cont_branch_selection = $('#cont_branch_selection');
	$cont_session_selection = $("#cont_session_selection");
	
	$course_selection.hide();
	$branch_selection.hide();
	$cont_course_selection.hide();
	$cont_branch_selection.hide();

               $('#form_submit').on('submit', function(e) {
                        if(!form_validation())
                                e.preventDefault();
                });

	
				
				$('#id_admn_based_on').on('change', function() {
                 	if(document.getElementById('id_admn_based_on').value == 'others')
                        document.getElementById('other_mode_of_admission').disabled=false;
					else
						document.getElementById('other_mode_of_admission').disabled=true;
                });
				
		
		$('#stu_type').on('change', function() {


                        if($('#stu_type').val() == 'jrf') {
                                document.getElementById('course_id').innerHTML = '<select id="course_id" name="course"><option value="phd">Ph.D</option></select>';
                                document.getElementById('branch_id').innerHTML = '<select id="branch_id" name="branch"><option value="na">Not Applicable</option></select>';
                        }
                        else if($('#stu_type').val() == 'pd') {
                                document.getElementById('course_id').innerHTML = '<select id="course_id" name="course"><option value="postdoc">Post Doc</option></select>';
                                document.getElementById('branch_id').innerHTML = '<select id="branch_id" name="branch"><option value="na">Not Applicable</option></select>';
                        }
                        else
                                options_of_courses();

                        if($('#stu_type').val() == 'jrf' || $('#stu_type').val() == 'pd')
                        {
                                document.getElementsByName('semester')[0].innerHTML = '<select name="semester"><option value="-1">Not Applicable</option></select>';
                        }
                        else
                        {
                                document.getElementsByName('semester')[0].innerHTML = '<select name="semester"><option value="1"  >1</option><option value="2"  >2</option><option value="3"  >3</option><option value="4"  >4</option><option value="5"  >5</option><option value="6"  >6</option><option value="7"  >7</option><option value="8"  >8</option><option value="9"  >9</option><option value="10"  >10</option></select>';
                        }
                });

                $('#depts').on('change', function() {
					$cont_course_selection.show();
					$course_selection.show();
                        if($('#stu_type').val() != 'jrf')
                                options_of_courses();
                });

                $('#course_id').on('change', function() {
					$cont_branch_selection.show();
					$branch_selection.show();
                        if($('#course_id').val() == 'na') {
                                $('#branch_id').html('<option value = "na" selected >Not Applicable</option>');
                        }
                        else
                                options_of_branches();
                });

                $('#id_admn_based_on').on('change', function() {
                        select_exam_scores();
                });
			
	     function form_validation()
        {
                if(!admission_based_on_validation())
                        return false;
                if(!student_type_validation())
                        return false;
                if(!course_branch_validation())
                        return false;
                //push_na_in_empty();
                return true;
        }
		
		function course_branch_validation()
        {
                var course = document.getElementById("course_id").value;
                var branch = document.getElementById("branch_id").value;
                if(branch == "none" || course == "none")
                {
                                alert("Branch or Course not selected or exists.")
                                return false;
                }
                else
                        return true;
        }
		function dept_validation()
		{
             var department = document.getElementById("depts").value;
			if(department == '' || department == 'select')
				{
					alert("Please select department.");
                                return false;
				}
		}

    function options_of_courses()
    {

		$.ajax({url : site_url("student_add_data/student_register_deo/get_courses/"+$('#depts').val()),
				success : function (result) {
					$('#course_id').html(result);
				}});
    }
	function options_of_branches() 
	{
		$.ajax({url : site_url("student_add_data/student_register_deo/get_branches/"+$('#depts').val()+"/"+$('#course_id').val()),
				success : function (result) {
					$('#branch_id').html(result);
				}});	
	}
		

        function admission_based_on_validation()
        {
             var admission_based_on = document.getElementById("id_admn_based_on").value;

			 if(admission_based_on.trim() == 'select' || admission_based_on.trim() == '')
			 {
				 alert("Please select the mode of admission.");
                                return false;
			}
			if(admission_based_on == 'others')
                {
                        var other_mode_of_admission = document.getElementById('other_mode_of_admission').value;
                        if(other_mode_of_admission.trim() == '')
                        {
                                alert("Please fill the other mode of admission.")
                                return false;
                        }
                        else
                                return true;
                }
                return true;
        }

        function student_type_validation()
        {
                var student_type = document.getElementById('stu_type').value;
				if(student_type == '' || student_type == 'select')
				{
					alert("Please select student type.");
                                return false;
				}
                if(student_type == 'others')
                {
                        var student_other_type = document.getElementById('student_other_type').value;
                        if(student_other_type.trim() == '')
                        {
                                alert('Please enter the other "Student Other Type".');
                                return false;
                        }
                        else
                                return true;
                }
                return true;
        }
		
});