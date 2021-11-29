$(document).ready(function(){
	$("button#add_course_button").on("click",function(e){
		$.ajax({
			url:"./config_inf/add_course",
			data: {
				"course_id" : $("#course_selection").val()
			},
			type : "POST",
			success : function(data){
				alert("Data added successfully");
				$("#add_course_table").append('<tr><td>'+$("#course_selection option:selected").text()+'</td><td><button value="Delete" class=" btn btn-danger delete_course" data-course_id="'+$("#course_selection").val()+'" type="button">Delete</button></td></tr>');
			},
			error : function(data){
				alert("Data already exists");
			}
		});
		e.preventDefault();
	});

	$("table#add_course_table").on("click","button.delete_course" ,function(e){
		$.ajax({
			url:"./config_inf/delete_course",
			data: {
				"course_id" : $(this).data("course_id")
			},
			type : "POST",
			success : function(data){
				alert("Data delete successfully");
				location.reload();
			},
			error : function(data){
				alert("Some error occured please try again later");
			}
		});
		e.preventDefault();
	});

	//Company Sector Part
	$("button#add_sector_button").on("click",function(e){
		$.ajax({
			url:"./config_inf/add_sector",
			data: {
				"sector_name" : $("#add_sector_text").val()
			},
			type : "POST",
			success : function(data){
				//console.log(data);
				alert("Data added successfully");
				location.reload();
				// $("#add_sector_table").append('<tr><td>'+$("#add_sector_text").val()+'</td><td><button value="Delete" class=" btn btn-danger delete_sector" data-course_id="'+$("#add_sector_text").val()+'" type="button">Delete</button></td></tr>');
			},
			error : function(data){
				alert("Data already exists");
			}
		});
		e.preventDefault();
	});

	$("table#add_sector_table").on("click","button.delete_sector" ,function(e){
		$.ajax({
			url:"./config_inf/delete_sector",
			data: {
				"sector_id" : $(this).data("sector_id")
			},
			type : "POST",
			success : function(data){
				alert("Data delete successfully");
				location.reload();
			},
			error : function(data){
				alert("Some error occured please try again later");
			}
		});
		e.preventDefault();
	});

	//Company Category Part
	$("button#add_category_button").on("click",function(e){
		$.ajax({
			url:"./config_inf/add_category",
			data: {
				"category_name" : $("#add_category_text").val()
			},
			type : "POST",
			success : function(data){
				//console.log(data);
				alert("Data added successfully");
				location.reload();
				// $("#add_category_table").append('<tr><td>'+$("#add_category_text").val()+'</td><td><button value="Delete" class=" btn btn-danger delete_category" data-course_id="'+$("#add_category_text").val()+'" type="button">Delete</button></td></tr>');
			},
			error : function(data){
				alert("Data already exists");
			}
		});
		e.preventDefault();
	});

	$("table#add_category_table").on("click","button.delete_category" ,function(e){
		$.ajax({
			url:"./config_inf/delete_category",
			data: {
				"category_id" : $(this).data("category_id")
			},
			type : "POST",
			success : function(data){
				alert("Data delete successfully");
				location.reload();
			},
			error : function(data){
				alert("Some error occured please try again later");
			}
		});
		e.preventDefault();
	});
});

