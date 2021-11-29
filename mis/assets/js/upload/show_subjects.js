$(document).ready(function(){
	$.ajax({
		url : site_url("upload/upload_attendance_ajax/get_session_year"),
		success:function(result){
			$('#session_year_attendance').html(result);
		}
	});
	

});