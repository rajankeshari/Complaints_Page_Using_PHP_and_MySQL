$(document).ready(function() {
		
	
	$adm_based_on_selection= $('#adm_based');
	$mod_adm_selection=$('#mod_adm');
	$other_rank=$('#other_rank');
	$dept_selection = $('#depts');
	$course_selection = $('#course');
	$branch_selection = $('#branch');
	
	$cont_mod_adm_selection= $('#cont_mod_adm');
	$cont_other_rank=$('#cont_other_rank');
	$cont_course_selection = $('#cont_course_selection');
	$cont_branch_selection = $('#cont_branch_selection');
	$cont_session_selection = $("#cont_session_selection");
	
	$mod_adm_selection.hide();
	$other_rank.hide();
	$course_selection.hide();
	$branch_selection.hide();
	$cont_mod_adm_selection.hide();
	$cont_other_rank.hide();
	$cont_course_selection.hide();
	$cont_branch_selection.hide();

           	
                
				 $('#adm_based').on('change', function() {
					
                        if($('#adm_based').val() == 'others')
						{
							$cont_mod_adm_selection.show();
					        $mod_adm_selection.show();
							$other_rank.show();
							$cont_other_rank.show();
						}  
						else
						{
							$cont_mod_adm_selection.hide();
					        $mod_adm_selection.hide();
							$other_rank.hide();
							$cont_other_rank.hide();
						}
						
						if(document.getElementById("adm_based").value != 'select')
						{
							document.getElementById("adm_based_error").innerHTML='';
						}
                });
				 
				$('#stu_type').on('change', function() {
					
                        if($('#stu_type').val() == 'jrf' || $('#stu_type').val() == 'pd')
						{
							$cont_course_selection.hide();
							$course_selection.hide();
                            $cont_branch_selection.hide();
					         $branch_selection.hide();
						}
						
						if(document.getElementById("stu_type").value != 'select')
						{
							document.getElementById("stu_type_error").innerHTML='';
						}
                }); 
				 
				$('#cat').on('change', function() {
						
						if(document.getElementById("cat").value != 'select')
						document.getElementById("cat_error").innerHTML='';
						
				 });	  

                $('#depts').on('change', function() {
					
                        if($('#stu_type').val() != 'jrf' && $('#stu_type').val() != 'pd')
						{
							$cont_course_selection.show();
							$course_selection.show();
                            options_of_courses();
						}
						
						if(document.getElementById("depts").value != 'select')
						{
							document.getElementById("dept_error").innerHTML='';
						}
                });

                $('#course').on('change', function() {
					
                         if($('#stu_type').val() != 'jrf' && $('#stu_type').val() != 'pd')
						{
							$cont_branch_selection.show();
					         $branch_selection.show();
                                options_of_branches();
						}
						
						if(document.getElementById("course").value != 'select')
						{
							document.getElementById("course_error").innerHTML='';
						}
                });
				
				$('#branch').on('change', function() {
					
						
						if(document.getElementById("branch").value != 'select')
						{
							document.getElementById("branch_error").innerHTML='';
						}
                });
				
				
				
			/*	 $('#update_detail').on('submit', function(event) {
                        if(!form_validation())
						event.preventDefault();
                                
                });*/
				 
				 
				 
			/*	 function form_validation()
		         {
					 var Adm = document.getElementById("Adm_no_others").value; 
					 var Adm1 = document.getElementById("Adm_no_others1").value;
					 
					 if(Adm!=Adm1)
					 {
						 alert("Admission no. not Matching.");
						 return false;
					 }
					 
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
					document.getElementById("form_id").submit();
					return true;
		         }
				*/

                
    function options_of_courses()
    {

		$.ajax({url : site_url("new_student_admission/student_register_deo_others/get_courses?p="+$('#depts').val()),
				success : function (result) {
					$('#course').html(result);
				}});
    }
	
	function options_of_branches() 
	{
		$.ajax({url : site_url("new_student_admission/student_register_deo_others/get_branches?p="+$('#depts').val()+"&q="+$('#course').val()),
				success : function (result) {
					$('#branch').html(result);
				}});	
	}
	
	
	
	

	

       
		
});



function form_validation()
		         {
					 var x=document.getElementById("gen").checked;
					  var y=document.getElementById("gen1").checked;
					   var z=document.getElementById("gen2").checked;
					 if(x==false&&y==false&&z==false)
					 {
						 document.getElementById("gen_error").innerHTML='*please select this field';
						 return false;
					 }

 
					 var cat = document.getElementById("cat").value;
			        if(cat == 'select')
				    {
					   document.getElementById("cat_error").innerHTML='*please select this field';
                                return false;
				    }
					else
					{
						 document.getElementById("cat_error").innerHTML='';
					}
					 
					 
					 var Adm = document.getElementById("Adm_no_others").value; 
					 var Adm1 = document.getElementById("Adm_no_others1").value;
					 
					 if(Adm!=Adm1)
					 {
						 document.getElementById("Adm_no_error").innerHTML='*admission no. not matching';
						 //alert("Admission no. not Matching.");
						 return false;
					 }
					 else if(Adm==''||Adm1=='')
					 {
						 document.getElementById("Adm_no_error").innerHTML='*please fill the field';
						 //alert("Admission no. not Matching.");
						 return false;
					 }
					 else
					 {
						   document.getElementById("Adm_no_error").innerHTML='';
					 }
					 
					 
					 var adm_based = document.getElementById("adm_based").value;
			        if(adm_based == 'select')
				    {
					   document.getElementById("adm_based_error").innerHTML='*please select this field';
                                return false;
				    }
					else
					{
						 document.getElementById("adm_based_error").innerHTML='';
					}
					
					if(adm_based == 'others')
					{
						var mod= document.getElementById("mod_adm").value;
						if(mod=='')
						{
							document.getElementById("mod_error").innerHTML='*please fill this field';
							return false;
						}
					}
					
					
					var student_type = document.getElementById("stu_type").value;
					
					
					if(student_type == 'select')
				    {
					   document.getElementById("stu_type_error").innerHTML='*please select this field';
                                return false;
				    }
					else
					{
						document.getElementById("stu_type_error").innerHTML='';
					}
					 
					 
					 
                    var department = document.getElementById("depts").value;
			        if(department == 'select')
				    {
					   document.getElementById("dept_error").innerHTML='*please select this field';
                                return false;
				    }
					else
					{
						document.getElementById("dept_error").innerHTML='';
					}
					
					
				
					var course = document.getElementById("course").value;
			        if(course == 'select')
				    {
					    if(student_type!='jrf'&&student_type!='pd')
						{
					      document.getElementById("course_error").innerHTML='*please select this field';
						  //alert('please select course');
                                return false;
						}
				    }
					else
					{
						document.getElementById("course_error").innerHTML='';
					}
					
					var branch = document.getElementById("branch").value;
			        if(branch == 'select')
				    {
						if(student_type!='jrf'&&student_type!='pd')
					   {
					      document.getElementById("branch_error").innerHTML='*please select this field';
                                return false;
					   }
				    }
					else
					{
						document.getElementById("branch_error").innerHTML='';
					}
					
					
					
					//document.getElementById("form_id").submit();
					return true;
		         }