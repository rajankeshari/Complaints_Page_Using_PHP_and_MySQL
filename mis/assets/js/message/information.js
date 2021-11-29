function suggest(text)
	{	
		//alert(link);
		var xmlhttp = getxmlhttp();
		if(text==""){
			return false;
		}
		xmlhttp.onreadystatechange = function()
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status==200)
			{
				//document.getElementById("content").hide();
				document.getElementById("content").innerHTML = xmlhttp.responseText;
				//$(".loading").hide();
			}
		}
		xmlhttp.open("POST", site_url("message/view_notice/search/archived/"+text),true);
		xmlhttp.send();
		return false;

	}
function index(no_of_individuals)
{ 
	// alert(no_of_individuals);
	
	$.ajax({
		url: site_url("message/message_ajax/index/"+no_of_individuals),
		success: function(result){
			console.log(result);
			$("#groups").html(result);
		}
	});
}
function index_individual(no_of_individuals,notice_id)
{
	$.ajax({
		url: site_url("message/message_ajax/index_individual/"+no_of_individuals+"/"+notice_id),
		success: function(result){
			$("#groups").html(result);
		}
	});
}
function del_group()
{
	$.ajax({
		url: site_url("message/message_ajax/del_group/"+del_grp_selection),
		success: function(result){
			$("#groups").html(result);
		}
	});
}
function add_member(data1,data2,data3,data4,data5)
{
	//window.alert("member with id "+data3+"has been added in group "+data1);
	console.log(data1+' '+data2+' '+data3+' '+data4+' '+data5);
	$.ajax({
		url: site_url("message/message_ajax/add_member/"+data1+"/"+data2+"/"+data3+"/"+data4),
		success: function(result){
			$("#move_details_of_sent_files").html(result);
		}
	});
}
function add_member_all(data1,data2)
{
	console.log(data1,data2);
	$.ajax({
		url: site_url("message/message_ajax/add_member_all/"+data1+"/"+data2),
		success: function(result){
			$("#move_details_of_sent_files").html(result);
		}
	});
}
function search_group(data1)
{
	//window.alert("member with id "+data3+"has been added in group "+data1);
	$.ajax({
		url: site_url("message/message_ajax/search_group/"+data1),
		success: function(result){
			$("#groups_name_id2").html(result);
		}
	});
}

function error()
{
	window.alert("all entries should be filled");
}
function clickEvent(data1,data2,data3,data4)
{
	$.ajax({
		url: site_url("message/message_ajax/rm_member/"+data1+"/"+data2+"/"+data3+"/"+data4),
		success: function(result){
			$("#move_details_of_sent_files").html(result);
		}
	});
}
function get_courses(dept,course)
{
	console.log(dept+' '+course);
	$.ajax({
		url: site_url("message/message_ajax/get_courses/"+dept+'/'+course),
		success: function(result){
			$("#course_col").html(result);
		}
	});
}
function semester(course,sem)
{
	
	$.ajax({
		url: site_url("message/message_ajax/semester/"+course+'/'+sem),
		success: function(result){
			$("#sem").html(result);
		}
	});
}
