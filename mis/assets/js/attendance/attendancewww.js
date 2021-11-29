$(document).ready(function(){
	$.ajax({
		url : site_url("attendance/attendance_ajax/get_session_year"),
		success:function(result){
			$('.gS').html(result);
		}
	});
	$('#session_attendance').on('change', function() {
		var session_year = $('#session_year_attendance').val();  
		$.ajax({url : site_url("attendance/attendance_ajax/get_subject/"+this.value+"/"+session_year),
			success : function (result) {
				$('#subject_attendance').html(result);
				}});
	});
        $('#session_attendance_honour').on('change', function() {
		var session_year = $('#session_year_attendance_h').val();  
		$.ajax({url : site_url("attendance/attendance_ajax/get_subject/"+this.value+"/"+session_year+"/honour"),
			success : function (result) {
				$('#subject_attendance_h').html(result);
				}});
	});
        $('#session_attendance_minor').on('change', function() {
		var session_year = $('#session_year_attendance_m').val();  
		$.ajax({url : site_url("attendance/attendance_ajax/get_subject/"+this.value+"/"+session_year+"/minor"),
			success : function (result) {
				$('#subject_attendance_m').html(result);
				}});
	});
	$('#subject_attendance').on('change', function() {
		$.ajax({url:site_url("attendance/attendance_ajax/get_branch/"+this.value),
			success : function(result) {
				$('#branch_attendance').html(result);
		}});

	});
        
        $('#subject_attendance_h').on('change', function() {
			$.ajax({url:site_url("attendance/attendance_ajax/get_branch/"+this.value),
				success : function(result) {
					$('#branch_attendance_h').html(result);
		}});

	});
        $('#subject_attendance_m').on('change', function() {
		$.ajax({url:site_url("attendance/attendance_ajax/get_branch/"+this.value),
			success : function(result) {
				$('#branch_attendance_m').html(result);
		}});

	});
	$('#branch_attendance').on('change', function() {
		var session_year = $('#session_year_attendance').val();
		var session = $('#session_attendance').val();
		var subject = $('#subject_attendance').val();
	
		$.ajax({url : site_url("attendance/attendance_ajax/get_course1"),
			type:"POST",
			data:{
				"branch":this.value,
				"session_year":session_year,
				"session":session,
				"subject":subject
			},
			success : function (result) {
				$('#course_attendance').html(result);
				}});
	});


	$('#session_attendance_c').on('change', function() {
		var session_year = $('#session_year_attendance_c').val();  
		$.ajax({url : site_url("attendance/attendance_ajax/get_subject_common/"+this.value+"/"+session_year),
			success : function (result) {
				$('#subject_attendance_c').html(result);
				}});
	});

	$('#subject_attendance_c').on('change',function() {
		var session_year = $('#session_year_attendance_c').val();
		var session = $('#session_attendance_c').val();
		$.ajax({url : site_url("attendance/attendance_ajax/get_section_common1/"+this.value+"/"+session+"/"+session_year),
			success : function (result) {
				$('#section_id').html(result);
			}});
	});

	$('#session_attendance_c2').on('change',function() {
		var session_year=$('#session_year_attendance_c2').val();
		$.ajax({url : site_url("attendance/attendance_ajax/get_section_common2/"+session_year),
			success : function (result) {
				$('#section_id2').html(result);
			}});
	});


});