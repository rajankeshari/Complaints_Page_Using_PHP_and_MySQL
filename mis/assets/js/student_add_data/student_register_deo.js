$(document).ready(function() {
	
	$iit_dept_selection = $('#iit_depts');
	$dept_selection = $('.depts');
	
	$iit_course_selection = $('#iit_course_id');
	$gate_course_selection = $('#gate_course_id');
	$cat_course_selection = $('#cat_course_id');
	$ism_course_selection = $('#ism_course_id');
	$other_course_selection = $('#other_course_id');
	$course_selection = $('.course');
	
	$iit_branch_selection = $('#iit_branch_id');
	$gate_branch_selection = $('#gate_branch_id');
	$cat_branch_selection = $('#cat_branch_id');
	$ism_branch_selection = $('#ism_branch_id');
	$other_branch_selection = $('#other_branch_id');
	$branch_selection = $('.branch');
	
	$cont_course_selection = $('.cont_course_selection');
	
	$iit_cont_course_selection = $('#iit_cont_course_selection');
	$gate_cont_course_selection = $('#gate_cont_course_selection');
	$cat_cont_course_selection = $('#cat_cont_course_selection');
	$other_cont_course_selection = $('#other_cont_course_selection');
	$ism_cont_course_selection = $('#ism_cont_course_selection');

	$iit_cont_branch_selection = $('#iit_cont_branch_selection');
	$gate_cont_branch_selection = $('#gate_cont_branch_selection');
	$cat_cont_branch_selection = $('#cat_cont_branch_selection');
	$other_cont_branch_selection = $('#other_cont_branch_selection');
	$ism_cont_branch_selection = $('#ism_cont_branch_selection');

	$cont_branch_selection = $('.cont_branch_selection')

	$course_selection.hide();
	$branch_selection.hide();
	$cont_course_selection.hide();
	$cont_branch_selection.hide();

               $('#iit_form_submit').on('submit', function(e) {
                        if(!form_validation('iit'))
                                e.preventDefault();
                });
				
				$('#gate_form_submit').on('submit', function(e) {
                        if(!form_validation('gate'))
                                e.preventDefault();
                });
				$('#cat_form_submit').on('submit', function(e) {
                        if(!form_validation('cat'))
                                e.preventDefault();
                });
				$('#ism_form_submit').on('submit', function(e) {
                        if(!form_validation('ism'))
                                e.preventDefault();
                });
				
				$('#other_form_submit').on('submit', function(e) {
                        if(!form_validation('other'))
                                e.preventDefault();
                });
				
				
				$('#ism_stu_type').on('change', function() {

                        if($('#ism_stu_type').val() == 'jrf') {
							
                                document.getElementById('ism_course_id').innerHTML = '<select id="ism_course_id" class="course" name="course"><option value="phd">Ph.D</option></select>';
                                document.getElementById('ism_branch_id').innerHTML = '<select id="ism_branch_id" class="branch" name="branch"><option value="na">Not Applicable</option></select>';
                        }
                        else if($('#ism_stu_type').val() == 'pd') {
							
                                document.getElementById('ism_course_id').innerHTML = '<select id="ism_course_id" class="course" name="course"><option value="postdoc">Post Doc</option></select>';
                                document.getElementById('ism_branch_id').innerHTML = '<select id="ism_branch_id" class="branch" name="branch"><option value="na">Not Applicable</option></select>';
                        }
                        else
                                options_of_courses('ism');

                });
				
				$('#other_stu_type').on('change', function() {

                        if($('#other_stu_type').val() == 'jrf') {
                                document.getElementById('other_course_id').innerHTML = '<select id="other_course_id" class="course" name="course"><option value="phd">Ph.D</option></select>';
                                document.getElementById('other_branch_id').innerHTML = '<select id="other_branch_id" class="branch" name="branch"><option value="na">Not Applicable</option></select>';
                        }
                        else if($('#other_stu_type').val() == 'pd') {
                                document.getElementById('other_course_id').innerHTML = '<select id="other_course_id" class="course" name="course"><option value="postdoc">Post Doc</option></select>';
                                document.getElementById('other_branch_id').innerHTML = '<select id="other_branch_id" class="branch" name="branch"><option value="na">Not Applicable</option></select>';
                        }
                        else
                                options_of_courses('other');

                });

                $('#iit_depts').on('change', function() {
					$iit_cont_course_selection.show();
					$iit_course_selection.show();
                                options_of_courses('iit');
                });
				
				$('#gate_depts').on('change', function() {
					$gate_cont_course_selection.show();
					$gate_course_selection.show();
                        if($('#gate_stu_type').val() != 'jrf')
                                options_of_courses('gate');
                });
				
				$('#cat_depts').on('change', function() {
					$cat_cont_course_selection.show();
					$cat_course_selection.show();
                        if($('#cat_stu_type').val() != 'jrf')
                                options_of_courses('cat');
                });
				
				$('#ism_depts').on('change', function() {
					$ism_cont_course_selection.show();
					$ism_course_selection.show();
                        if($('#ism_stu_type').val() != 'jrf')
                                options_of_courses('ism');
                });
				
				$('#other_depts').on('change', function() {
					$other_cont_course_selection.show();
					$other_course_selection.show();
                        if($('#other_stu_type').val() != 'jrf')
                                options_of_courses('other');
                });


                $('#iit_course_id').on('change', function() {
					$iit_cont_branch_selection.show();
					$iit_branch_selection.show();
                        if($('#iit_course_id').val() == 'na') {
                                $('#iit_branch_id').html('<option value = "na" selected >Not Applicable</option>');
                        }
                        else
                                options_of_branches('iit');
                });
				
				$('#gate_course_id').on('change', function() {
					$gate_cont_branch_selection.show();
					$gate_branch_selection.show();
                        if($('#gate_course_id').val() == 'na') {
                                $('#gate_branch_id').html('<option value = "na" selected >Not Applicable</option>');
                        }
                        else
                                options_of_branches('gate');
                });
				
				$('#cat_course_id').on('change', function() {
					$cat_cont_branch_selection.show();
					$cat_branch_selection.show();
                        if($('#cat_course_id').val() == 'na') {
                                $('#cat_branch_id').html('<option value = "na" selected >Not Applicable</option>');
                        }
                        else
                                options_of_branches('cat');
                });
				
				$('#ism_course_id').on('change', function() {
					$ism_cont_branch_selection.show();
					$ism_branch_selection.show();
                        if($('#ism_course_id').val() == 'na') {
                                $('#ism_branch_id').html('<option value = "na" selected >Not Applicable</option>');
                        }
                        else
                                options_of_branches('ism');
                });
				
				$('#other_course_id').on('change', function() {
					$other_cont_branch_selection.show();
					$other_branch_selection.show();
                        if($('#other_course_id').val() == 'na') {
                                $('#other_branch_id').html('<option value = "na" selected >Not Applicable</option>');
                        }
                        else
                                options_of_branches('other');
                });
			
	     function form_validation(type)
        {
                if( type==='other' && !admission_based_on_validation(type))
                        return false;
                if(!student_type_validation(type))
                        return false;
                if(!course_branch_validation(type))
                        return false;
                return true;
        }
		
		function course_branch_validation(type)
        {
			var dept = document.getElementById(type+"_dept").value;
			if(dept=='' )
			{
				alert("Department not selected.");
				return false;
			}
			else
			{
                var course = document.getElementById(type+"_course_id").value;
                var branch = document.getElementById(type+"_branch_id").value;
                if(branch == "none" || course == "none")
                {
                                alert("Branch or Course not selected or exists.")
                                return false;
                }
                else
                        return true;
			}
        }
		function dept_validation(type)
		{
             var department = document.getElementById(type+"_depts").value;
			if(department == '' || department == 'select')
				{
					alert("Please select department.");
                                return false;
				}
		}

    function options_of_courses(type)
    {

		$.ajax({url : site_url("student_add_data/student_register_deo/get_courses/"+$('#'+type+'_depts').val()),
				success : function (result) {
					$('#'+type+'_course_id').html(result);
				}});
    }
	function options_of_branches(type) 
	{
		$.ajax({url : site_url("student_add_data/student_register_deo/get_branches/"+$('#'+type+'_depts').val()+"/"+$('#'+type+'_course_id').val()),
				success : function (result) {
					$('#'+type+'_branch_id').html(result);
				}});	
	}
		

        function admission_based_on_validation(type)
        {
			if(type == 'other')
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

        function student_type_validation(type)
        {
                var student_type = document.getElementById(type+'_stu_type').value;
				if(student_type=='' || student_type == 'select')
				{
					alert("Please select student type.");
                                return false;
				}
                return true;
        }
		
});