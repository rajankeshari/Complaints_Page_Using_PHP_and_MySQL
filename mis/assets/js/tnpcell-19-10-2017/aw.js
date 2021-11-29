
$(document).ready(function(){

	$add_course_form = $("#allow_to_edit_cv");
	$box_form = $("#box_form");
	$opt_selection = $('#opt_selection')
	$dept_selection = $('#dept_selection');
	$course_selection = $('#course_selection');
	$admn_no = $("#admn_no");
	$rmk = $('#rmk');
	
	$cont_course_selection = $('#cont_course_selection');
	$cont_dept_selection = $('#cont_dept_selection');
	$cont_admn_no = $('#cont_admn_no');
	$cont_rmk = $('#cont_rmk');
	
	
	$course_selection.hide();
	$admn_no.hide();
	$dept_selection.hide();
	$rmk.hide();
	
	$cont_course_selection.hide();
	$cont_dept_selection.hide();
	$cont_admn_no.hide();
	$cont_rmk.hide()
	
	$duration = 1;
	
	function add_course(){
		
		$box_form.showLoading();
		$.ajax({url:site_url("tnpcell/allow_to_edit_cv/json_get_course/"+$dept_selection.find(':selected').val()),
			success:function(data){
				var base_str = "<option value = '' selected='selected' disabled>Select Course</option>";
				for($d=0 ; $d < data.length;$d++) {
					base_str += "<option data-duration='"+data[$d]['duration']+"' value='"+ data[$d]['id']+"'>"+data[$d]["name"]+"</option>";
				}
				
				$cont_course_selection.show();
				$course_selection.show().html(base_str);
				$select_course = $('select#course_selection');
				$select_course.on('change',function(){
					$admn_no.hide();
					$rmk.hide();
				});
				$box_form.hideLoading();
			},
			type:"POST",
			
			dataType:"json",
			fail:function(error){
				console.log(error);
				$box_form.hideLoading();
			}
		});
	}
  function add_dept()
   {
     	$box_form.showLoading();
		$.ajax({url:site_url("tnpcell/allow_to_edit_cv/json_get_dept/"),
			success:function(data){
				var base_str = "<option value = '' selected='selected' disabled>Select Dept</option>";
				//window.(base_str);
				//die();
				for($d=0 ; $d < data.length;$d++) {
					base_str += "<option data-duration='"+data[$d]['duration']+"' value='"+ data[$d]['id']+"'>"+data[$d]["name"]+"</option>";
				}
				
				$cont_dept_selection.show();
				$dept_selection.show().html(base_str);
				//window.alert(base_str);
				$select_dept = $('select#dept_selection');
				$select_dept.on('change',function(){
					$admn_no.hide();
					$rmk.hide();
					add_course();
				});
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
	$opt_selection.change(function(){

	    if($opt_selection.val()==1)
	    {
	    	$course_selection.hide();
	        $admn_no.hide();
	        $rmk.hide();
	        $dept_selection.hide();
	        $cont_course_selection.hide();
	        $cont_dept_selection.hide();
	        $cont_admn_no.hide();
	        $cont_rmk.hide();

	    }
	   else if($opt_selection.val()==2){
	    	$admn_no.hide();
	    	$rmk.hide();
	    	$course_selection.hide();
	    	$cont_course_selection.hide();
	        $cont_admn_no.hide();
	        $dept_selection.hide();
	    	$cont_dept_selection.hide();
            $cont_rmk.hide();
		    add_dept();
	     }
	     else
	     {
	     	 $course_selection.hide();
	         $dept_selection.hide();
	         $cont_course_selection.hide();
	         $cont_dept_selection.hide();
	         $cont_admn_no.show();
	     	 $admn_no.show();
	     	 $cont_rmk.show();
	     	 $rmk.show();
	     }
	});

});