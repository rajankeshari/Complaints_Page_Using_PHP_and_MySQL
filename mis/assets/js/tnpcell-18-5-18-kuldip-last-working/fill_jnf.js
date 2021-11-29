/*
* fill_inf.js -  javascript file used in fill_inf_details.php
*/
$(document).ready(function(){

	$box_form = $("#box_form");
	$add_course_form = $("#add_course_form");
	//$form_table = $("#form_table");
	$dept_selection = $('#dept_selection');
	$course_selection = $('#course_selection');
	$branch_selection = $('#branch_selection');
	
	$cont_course_selection = $('#cont_course_selection');
	$cont_branch_selection = $('#cont_branch_selection');
	$add_branch = $('#add_branch');
	
	$course_selection.hide();
	$branch_selection.hide();
	
	$cont_course_selection.hide();
	$cont_branch_selection.hide();

	$duration = 1;
	
	function add_course(){
		
		$box_form.showLoading();
		$.ajax({url:site_url("course_structure/add/json_get_course/"+$dept_selection.find(':selected').val()),
			success:function(data){
				var base_str = "<option value = '' selected='selected' disabled>Select Course</option>";
				for($d=0 ; $d < data.length;$d++) {
					base_str += "<option data-duration='"+data[$d]['duration']+"' value='"+ data[$d]['id']+"'>"+data[$d]["name"]+"</option>";
				}
				
				$cont_course_selection.show();
				$course_selection.show().html(base_str);
				$select_course = $('select#course_selection');
				$select_course.on('change',function(){
					$branch_selection.hide();

					$cont_branch_selection.hide();
					
					add_branch(parseInt($('#course_selection option:selected').data('duration')));
				});
				$box_form.hideLoading();
			},
			type:"POST",
			//data :JSON.stringify({course:$course_selection.find(':selected').val()}),
			dataType:"json",
			fail:function(error){
			$box_form.hideLoading();
				console.log(error);
			}
		});
	}
	
	
	function add_branch(duration){
		
		if($course_selection.val() == "comm")
			alert("To add for Honour, please visit CourseStructure->add course structure->For 1st Year Common");
		else
		{
			$course_selection = $('#course_selection');
			$dept_selection = $('#dept_selection');
			//alert($course_selection.find(':selected').val());
			$box_form.showLoading();
			$.ajax({url:site_url("course_structure/add/json_get_branch/"+$course_selection.find(':selected').val()+"/"+$dept_selection.find(':selected').val()),
				success:function(data){
					base_str_branch = "<option value = '' selected = 'selected' disabled>Select Branch</option>";
					for($d=0 ; $d < data.length;$d++){
						base_str_branch += "<option value=\""+ data[$d]["id"]+"\" data-cb-id="+data[$d]['course_branch_id']+">"+data[$d]["name"]+"</option>";
					}
					//base_str_branch += "<option>Select Branch</option>";
					
					$cont_branch_selection.show();
					$branch_selection.show().html(base_str_branch);
					
					$box_form.hideLoading();
				},
				type:"POST",
				//data :JSON.stringify({course:$course_selection.find(':selected').val()}),
				dataType:"json",
				fail:function(error){
					console.log(error);
					$box_form.hideLoading();
				}
			});
		}
	}
	
	$add_branch_table = $('#add_branch_table');
	$cb_add_tbody = $('#cb_add');
	$cb_container = $('#cb_container');
	
	$add_branch.on('click',function(){
		if($dept_selection.val() && $dept_selection.val()!== 'comm'){
			if($course_selection.val()){
				if($branch_selection.val()){
					$branch_selection.find(':selected').data('cb-id');
					
					//Add Data to table
					str = "<tr class='"+$branch_selection.find(':selected').data('cb-id')+"'><td>";
					str += $dept_selection.find(':selected').text();
					str += "</td>";
					str += "<td>";
					str += $course_selection.find(':selected').text();
					str += "</td>";
					str += "<td>";
					str += $branch_selection.find(':selected').text();
					str += "</td>";
					str += "<td>";
					str += "<button class='btn btn-danger delete_branch' data-cb-id="+$branch_selection.find(':selected').data('cb-id')+"><i class='fa fa-times'></i> &nbsp;Delete</button>";
					str += "</td>";
					str += "</tr>";
					$cb_add_tbody.append(str);
					$add_branch_table.removeClass('hide');
					
					// Add data to hidden div as input
					str = "<input type='hidden' class='"+$branch_selection.find(':selected').data('cb-id')+"' name='eligible_cb[]' value='"+$branch_selection.find(':selected').data('cb-id')+'.'+$course_selection.find(':selected').data('duration')+"' />";
					$cb_container.append(str);

					$dept_selection.prop('selectedIndex',1);
					$cont_course_selection.hide();
					$cont_branch_selection.hide();
					//$dept_selection.val($dept_selection.prop('defaultSelected'));

					//console.log($dept_selection.val(),$course_selection.val(),$branch_selection.val());
				}
			}
		}
	});
	
	$('#add_branch_table').on('click', 'button.delete_branch',function(e){
		e.preventDefault();
		//console.log(e);
		cb = $(this).data('cb-id');
		$("."+cb).remove();
		if($cb_add_tbody.children().length === 0){
			$add_branch_table.addClass('hide');
		}
	});

	$dept_selection.change(function(){
		$("#branch_selection").hide();
		$course_selection = $('#course_selection');
		$("#cont_branch_selection").hide();
		if($dept_selection.val() == "comm")
			alert("To add for 1st Year please visit CourseStructure->add course structure->for 1st year Common");
		else
			add_course();
	});
	
	/* Selecting all checkbox using single check*/
        $('input.all').on('ifToggled', function (event) {
            var chkToggle;
            var active_tab = $(this).val();
            $(this).is(':checked') ? chkToggle = "check" : chkToggle = "uncheck";
            $('input.selector' + active_tab +':not(.all)').iCheck(chkToggle);
        });
    /*end*/

});
