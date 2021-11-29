$(document).ready(function(){
	
        
	/*$.ajax({url : site_url("exam_absent_record/report_wef/get_wef"),
				success : function (result) {
					$('#effective').html(result);
				}});*/
     $.ajax({url : site_url("exam_absent_record/report_wef/get_dept_for_drexam"),
				success : function (result) {
					$('#department_name1').html(result);
				}});
                            $.ajax({url : site_url("exam_absent_record/report_wef/get_course_drexam"),
				success : function (result) {
					$('#course1').html(result);
				}});
                            $.ajax({url : site_url("exam_absent_record/report_wef/get_branch_drexam"),
				success : function (result) {
					$('#branch1').html(result);
				}});
    
    $("select[name='branch1']").on('change', function() {
			$.ajax({url : site_url("exam_absent_record/report_wef/get_wef/"+this.value+"/"+$("select[name='department_name1']").val()+"/"+$("select[name='course1']").val()),
				success : function (result) {
					$('#effective').html(result);
				}});
	});
        
        
         $("select[name='effective']").on('change', function() {
             
			$.ajax({url : site_url("exam_absent_record/report_wef/get_sub/"+this.value+"/"+$("select[name='semester']").val()),
				success : function (result) {
                                    
					$('#subject').html(result);
				}});// end of ajax
	}); //end of effective
	
	
        $("select[name='department_name1']").on('change', function() {
		$.ajax({url : site_url("student_view_report/report_new_file_ajax/get_course_dept_csshow/"+this.value),
				success : function (result) {
					$('#course1').html(result);
				}});
	});
        $("select[name='course1']").on('change', function() {
			$.ajax({url : site_url("student_view_report/report_new_file_ajax/get_branch_bycourse/"+this.value+"/"+$("select[name='department_name1']").val()),
				success : function (result) {
					$('#branch1').html(result);
				}});
        });
        
	});