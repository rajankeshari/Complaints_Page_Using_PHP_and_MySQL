$(document).ready(function(){
	$.ajax({
		url : site_url("view_attendance/view_attendance_sheet_ajax/get_session_year"),
		success:function(result){
			$('#session_year_attendance').html(result);
		}
	});
	$('#session_attendance').on('change', function() {
		var session_year = $('#session_year_attendance').val();  
		$.ajax({url : site_url("attendance/attendance_ajax/get_subject/"+this.value+"/"+session_year),
				success : function (result) {
					$('#subject_attendance').html(result);
				}});
	});
	$('#subject_attendance').on('change', function() {
		$.ajax({url:site_url("attendance/attendance_ajax/get_branch/"+this.value),
			success : function(result) {
				$('#branch_attendance').html(result);
		}});

	});
	$('#branch_attendance').on('change', function() {
		var session_year = $('#session_year_attendance').val();
		var session = $('#session_attendance').val();
		var subject = $('#subject_attendance').val();
		$.ajax({url : site_url("attendance/attendance_ajax/get_course/"+this.value+"/"+session_year+"/"+session+"/"+subject),
				error : function(err){
					alert(err.responseText);
				},
				success : function (result) {
					$('#course_attendance').html(result);
				}});
	});

});