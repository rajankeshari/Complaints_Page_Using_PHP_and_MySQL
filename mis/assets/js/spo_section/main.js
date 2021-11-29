$(document).ready(function(){

	$("#item").on("change",function(){
		var cat_code=$("#item").val();
		var url=base_url()+'index.php/spo_section/spo_ajax/get_brands_by_category_code';
		//console.log(url);
		$.ajax({
			url: url,
			type: 'POST',
			data: {
				code : cat_code,
			},      
			success: function(result)
			{
				$("#item_brand").html(result);
			}      
       });

	});
	
	$("#item_brand").on("change",function(){
		var brand_code=$("#item_brand").val();
		var cat_code=$("#item ").val();
		var url=base_url()+'index.php/spo_section/spo_ajax/get_quantity_by_item';
		console.log(cat_code);
		$.ajax({
			url: url,
			type: 'POST',
			data: {
				cat_code : cat_code,
				brand_code:brand_code,
			},      
			success: function(result)
			{
				//alert(result);
				$("#qty_available").prop('value',result);
			}      
       });

	});

//for report

	$("#cat_code").on("change",function(){
		var cat_code=$("#cat_code").val();
		var url=base_url()+'index.php/spo_section/spo_ajax/get_brands_for_report_by_category_code';
		//console.log(url);
		$.ajax({
			url: url,
			type: 'POST',
			data: {
				code : cat_code,
			},      
			success: function(result)
			{
				$("#brand_code").html(result);
				//console.log(result);
				//$("#brand_code").prop('value','All');
				//console.log($("#brand_code").prop('value'));
				
			}      
       });

	});

});


function validateAdm(e)
{   
	// var user_id=$(e).text();
	var id=$(e).attr('id'); 
	id="#"+id;
	var user_id=$(e).val();
	var cat_code=$('#code').val();
	console.log(cat_code); 
	var url=base_url()+'index.php/spo_section/spo_ajax/validate_user_id';
    console.log(url);
	$.ajax({
		url: url,
		type: 'POST',
		data: {
			user_id: user_id,
			cat_code: cat_code,
		},      
		success: function(result)
		{   
			if(result==1)
			{
				$(e).css('color','green');
				$(e).prop('title','valid').tooltip(); 
				$(e).prop('title',"");
			}
			else if(result==2)
			{
				$(e).css('color','red');
				$(e).prop('title','Invalid Admission Number').tooltip(); 
				$(e).prop('title',""); 
			}
			else if(result==3)
			{
				$(e).css('color','red');
				$(e).prop('title','Already in a group of this category').tooltip(); 
				$(e).prop('title',""); 
			}					

		}      
	});
}

function printer()
{
	$("button").hide();
	$("a").hide();
	$(".flash-data").hide()
	window.print();
	$(".flash-data").show()
	$("button").show();
	$("a").show();
	
}

function showProfile(e)
{   
	var user_id=$(e).text();
	var id=$(e).attr('id'); 
	id="#"+id;
	console.log(id);   
		var url=base_url()+'index.php/spo_section/spo_ajax/get_user_profile_by_user_id';
		//console.log(cat_code);
		$.ajax({
			url: url,
			type: 'POST',
			data: {
				user_id: user_id,
			},      
			success: function(result)
			{
				//alert(result);
				$(id).prop('title',result).tooltip(); 
				$(id).prop('title',"");
			}      
       });
}



// function getIssueReport(){
// 	var dateType = $('#date_type').val();
// 	var startDate = $('#start_date').val();
// 	var endDate = $('#end_date').val();
// 	var status = $('#status').val();
// 	var admNo = $('#adm_no').val();
// 	var groupId = $('#group_id').val();
// 	var brandCode = $('#brand_code').val();
// 	var catCode = $('#cat_code').val();

// 	var url='https://misdev.iitism.ac.in/'+'index.php/spo_section/spo_ajax/get_issue_report';

// 	$.ajax({
// 		url:	url,
// 		type:	'POST',
// 		data:	{
// 			date_type: dateType,
// 			start_date: startDate,
// 			end_date: endDate,
// 			status: status,
// 			adm_no: admNo,
// 			group_id: groupId,
// 			brand_code: brandCode,
// 			cat_code: catCode,
//  		},
// 		success:	function(result){
// 			printReportTable(result)
// 		}
// 	});
// }

// function printReportTable(str)
// {
// 	var issue=JSON.parse(str);
// 	console.log(issue);
// 	  var report='';
// 	for (var i = 0; i < issue.length; i++) {
// 		report+='<tr>';
// 		report+='<td> G-'+issue[i]['code']+'-'+issue[i]['group_id']+'</td>';
// 		report+='<td>'+issue[i]['adm_no']+'</td>';
// 		report+='<td>'+issue[i]['date_issue']+'</td>';
// 		report+='<td>'+issue[i]['return_date']+'</td>';
// 		report+='<td>'+issue[i]['brand_name']+'</td>';
// 		report+='<td>'+issue[i]['cat_name']+'</td>';
// 		report+='<td>'+issue[i]['purpose']+'</td>';
// 		report+='<td>'+issue[i]['remarks']+'</td>';
// 		report+='<td>'+issue[i]['quantity_issued']+'</td>';
// 		report+='</tr>';
// 	}
	
// 	$("tbody").html(report);
	
	
// }



$(document).ready(function(){
	$("[name=reject]").on('click',function(eve){
		if(!confirm("Are you sure?")){
			eve.preventDefault();
		}
	});
});
