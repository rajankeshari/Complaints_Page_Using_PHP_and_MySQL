// JavaScript Document

$(document).ready(function(){
console.log("inside");
// this section manages inputs of add result section
$add_result_type = $("#ddl_add_result_by");
$add_result_company = $("#ddl_add_result_company");
$add_result_admission_no = $("#stu_admission_num");
$add_result_course = $("#ddl_add_result_course");
$add_result_branch = $("#ddl_add_result_branch");
$add_result_name = $("#ddl_add_result_name");

$cont_add_result_type = $("#cont_ddl_add_result_by");
$cont_add_result_company = $("#cont_ddl_add_result_company");
$cont_add_result_admission_no = $("#cont_stu_admission_num");
$cont_add_result_course = $("#cont_ddl_add_result_course");
$cont_add_result_branch = $("#cont_ddl_add_result_branch");
$cont_add_result_name = $("#cont_ddl_add_result_name");

$cont_add_result_course.hide();
$cont_add_result_branch.hide();
$cont_add_result_name.hide();

$add_result_course.hide();
$add_result_branch.hide();
$add_result_name.hide();


// this section manages inputs of view result section
$view_result_type = $("#ddl_view_result_by");
$view_result_company = $("#ddl_view_result_company");
$view_result_course = $("#ddl_view_result_course");
$view_result_branch = $("#ddl_view_result_branch");

$cont_view_result_type = $("#cont_ddl_view_result_by");
$cont_view_result_company = $("#cont_ddl_view_result_company");
$cont_view_result_course = $("#cont_ddl_view_result_course");
$cont_view_result_branch = $("#cont_ddl_view_result_branch");


$cont_view_result_course.hide();
$cont_view_result_branch.hide();
$view_result_course.hide();
$view_result_branch.hide();


$("#ddl_add_result_by").change(function(){
	if($("#ddl_add_result_by").val() == 1)
	{
		$cont_add_result_course.hide();
		$cont_add_result_branch.hide();
		$cont_add_result_name.hide();
		
		$add_result_course.hide();
		$add_result_branch.hide();
		$add_result_name.hide();
		
		$cont_add_result_admission_no.show();
		$add_result_admission_no.show();
		
	}
	else
	{
		$cont_add_result_admission_no.hide();
		$add_result_admission_no.hide();
		
		$cont_add_result_course.show();
		$add_result_course.show();
	}
});

$("#ddl_add_result_course").change(function(){
	$box_form = $("#box_stu_result");
		$box_form.showLoading();
		$.ajax({url:site_url("tnpcell/stu_placement/json_get_branch/"+$("#ddl_add_result_course").find(':selected').val()),
			success:function(data){
				console.log("succeess");
				
				var base_str = "<option value = '' selected='selected' disabled>Select Branch</option>";
				for($d=0 ; $d < data.length;$d++) {
					base_str += "<option  value='"+ data[$d]['id']+"'>"+data[$d]["name"]+"</option>";
				}
				
				$("#cont_ddl_add_result_branch").show();
				$("#ddl_add_result_branch").show();
				$("#ddl_add_result_branch").html(base_str);
				$box_form.hideLoading();
			},
			type:"POST",
			dataType:"json",
			fail:function(error){
				console.log(error);
				$box_form.hideLoading();
			}
		});
});

$("#ddl_add_result_branch").change(function(){
	$box_form = $("#box_stu_result");
		$box_form.showLoading();
		$.ajax({url:site_url("tnpcell/stu_placement/json_get_student_from_course_branch_for_placement/"+$("#ddl_add_result_course").find(':selected').val()+"/"+$
		("#ddl_add_result_branch").find(':selected').val()),
			success:function(data){
				console.log(data);	
				var base_str = "<option value = '' selected='selected' disabled>Select Name</option>";//<a href="http://stackoverflow.com/">
				for($d=0 ; $d < data.length;$d++) {
					base_str += "<option  value='"+ data[$d]['id']+"'>"+data[$d]["first_name"]+" "+data[$d]["middle_name"]+" "+data[$d]["last_name"
					]+"("+data[$d]['id']+")</option>";
				}
				$("#cont_ddl_add_result_name").show();
				$("#ddl_add_result_name").show().html(base_str);
				
				$box_form.hideLoading();
			},
			type:"POST",
			dataType:"json",
			fail:function(error){
				console.log(error);
				$box_form.hideLoading();
			}
		});
	
});



$("#ddl_view_result_by").change(function(){
		
	if($("#ddl_view_result_by").val() == 1)
	{
		$cont_view_result_course.hide();
		$cont_view_result_branch.hide();
		
		$view_result_course.hide();
		$view_result_branch.hide();
		
		$cont_view_result_company.show();
		$view_result_company.show();
		
	}
	else
	{
		$cont_view_result_company.hide();
		$view_result_company.hide();
		$cont_view_result_branch.hide();
		$view_result_branch.hide();
		
		$cont_view_result_course.show();
		$view_result_course.show();
		
	}
});

$("#ddl_view_result_course").change(function(){
	$box_form = $("#box_result");
		$box_form.showLoading();
		$.ajax({url:site_url("tnpcell/stu_placement/json_get_branch/"+$("#ddl_view_result_course").find(':selected').val()),
			success:function(data){
				console.log("succeess");
				
				var base_str = "<option value = '' selected='selected' disabled>Select Branch</option>";
				for($d=0 ; $d < data.length;$d++) {
					base_str += "<option  value='"+ data[$d]['id']+"'>"+data[$d]["name"]+"</option>";
				}
				
				$("#cont_ddl_view_result_branch").show();
				$("#ddl_view_result_branch").show();
				$("#ddl_view_result_branch").html(base_str);
				$box_form.hideLoading();
			},
			type:"POST",
			dataType:"json",
			fail:function(error){
				console.log(error);
				$box_form.hideLoading();
			}
		});
});



});

function selectStudent(select)
    {
      var option = select.options[select.selectedIndex];
      var ul = select.parentNode.parentNode.getElementsByTagName('ul')[0];
         
      var choices = ul.getElementsByTagName('input');
      for (var i = 0; i < choices.length; i++)
        if (choices[i].value == option.value)
          return;
         
      var li = document.createElement('li');
      var input = document.createElement('input');
      var text = document.createTextNode(option.firstChild.data);
         
      input.type = 'hidden';
      input.name = 'students[]';
      input.value = option.value;

      li.appendChild(input);
      li.appendChild(text);
      li.setAttribute('onclick', 'this.parentNode.removeChild(this);');     
        
      ul.appendChild(li);
    }
