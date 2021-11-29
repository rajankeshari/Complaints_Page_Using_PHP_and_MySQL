$(document).ready(function() {
	$('.emp_details').hide();
	
	$('#dailySubmit').on('submit', function(e) {
                        if(!d_emp_validation())
                          e.preventDefault();
                });
	
	$('#d_emp_no').on('change', function() {
			var emp_no = $('#d_emp_no').val();
			$('#d_emp_details').show();
			get_d_emp_details(emp_no);
		});
		
			function get_d_emp_details(emp_no) {
		$.ajax({url : site_url("employee_daily_rated/remove/show_daily_emp_details/"+emp_no),
				success : function (result) {
					$('#d_emp_details').html(result);
				}});
	}
	
	
	function d_emp_validation() {
	var empno=document.getElementById("d_emp_no").value;
	if( empno == '0')
		{
			alert("Please select an employee number.");
			return false;
		}
		else
		return true;
	}
});