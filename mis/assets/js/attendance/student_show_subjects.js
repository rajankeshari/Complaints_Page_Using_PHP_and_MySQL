$(document).ready(function(){
	$.ajax({
		url : site_url("attendance/student_attendance_ajax/get_session_year"),
		success:function(result){
                    
			$('#session_year_attendance').html(result);
		}
	});
	$('#session_attendance').on('change', function() {
		//alert(this.value);

		var session_year_val = $('#session_year_attendance').val();  
		//alert(session_year_val);
		$.ajax({url : site_url("attendance/student_attendance_ajax/get_semester/"+this.value+"/"+session_year_val ),
                    async:false   ,
		  
				success : function (result) {
					//alert(result);
					//$('#abs').html(result);
					$('#semester_attendance').html(result);
				}});
	});
	

});