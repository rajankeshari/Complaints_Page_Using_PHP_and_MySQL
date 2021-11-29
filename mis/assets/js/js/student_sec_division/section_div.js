$(document).ready(function(){
$.ajax({
		url : site_url("student_sec_division/section_view_details/setSessionYear"),
		success:function(result){
			$('#session_year_view').html(result);
		}
	});
});