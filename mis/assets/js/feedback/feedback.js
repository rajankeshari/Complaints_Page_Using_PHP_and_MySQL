function create_groups_fbs(num_of_groups)
{
	$.ajax({
		url: site_url("feedback/feedback_ajax/create_groups_fbs/"+num_of_groups),
		success: function(result){
			$("#groups").html(result);
		}
	});
}
function create_groups_fbr(num_of_groups)
{
	$.ajax({
		url: site_url("feedback/feedback_ajax/create_groups_fbr/"+num_of_groups),
		success: function(result){
			$("#groups").html(result);
		}
	});
}
function create_parameters_fbs(num_of_groups)
{
	$.ajax({
		url: site_url("feedback/feedback_ajax/create_parameters_fbs/"+num_of_groups),
		success: function(result){
			$("#parameters").html(result);
		}
	});
}
function create_parameters_fbr(num_of_groups)
{
	$.ajax({
		url: site_url("feedback/feedback_ajax/create_parameters_fbr/"+num_of_groups),
		success: function(result){
			$("#parameters").html(result);
		}
	});
}
function create_groups_fbe(num_of_groups)
{
	$.ajax({
		url: site_url("feedback/feedback_ajax/create_groups_fbe/"+num_of_groups),
		success: function(result){
			$("#groups").html(result);
		}
	});
}
function create_parameters_fbe(num_of_groups)
{
	$.ajax({
		url: site_url("feedback/feedback_ajax/create_parameters_fbe/"+num_of_groups),
		success: function(result){
			$("#parameters").html(result);
		}
	});
}
$(document).ready(function(){
	$('#departments').on('change', function() {
		$.ajax({url : site_url("feedback/dean/get_faculty_ajax/"+this.value),
				success : function (result) {
					$('#faculty').html(result);
				}});
	});
});