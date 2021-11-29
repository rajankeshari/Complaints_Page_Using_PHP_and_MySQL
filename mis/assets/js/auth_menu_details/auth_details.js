function onchange_authorization()
	{
		var auth=document.getElementById('auth').value;
		var menu_view=document.getElementById('menu_view');
		var xmlhttp;
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
		 	xmlhttp=new XMLHttpRequest();
		}
		else
	  	{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
	  	{
	  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		    {
			    menu_view.innerHTML = xmlhttp.responseText;
		    }
	  	}
		xmlhttp.open("POST",site_url("auth_menu_details/auth_menu_details_ajax/get_auth_menu/"+auth),true);
		xmlhttp.send();
		menu_view.innerHTML = "<center><i class=\"loading\"></i></center>";
	}
		function onload_auth(id)
	{
		var auth=document.getElementById(''+id+'');
		var xmlhttp;
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
		 	xmlhttp=new XMLHttpRequest();
		}
		else
	  	{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
	  	{
	  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		    {
			    auth.innerHTML += xmlhttp.responseText;
		    }
	  	}
		xmlhttp.open("POST",site_url("auth_menu_details/auth_menu_details_ajax/get_auths"),true);
		xmlhttp.send();
	}


function delete_menu(menu_id,auth_id)
{
	var result=confirm("Do you really want to delete this Menu detail ?");

		if(result==true)
		{   //var result=confirm("result");
			var menu_view=document.getElementById('menu_view');
			var xmlhttp;
			//confirm(menu_view);
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
			 	xmlhttp=new XMLHttpRequest();
			}
			else
		  	{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
		  	{
		  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
			    {
				    menu_view.innerHTML = xmlhttp.responseText;
			    }
			    //console.log(xmlhttp.status);
		  	}
		  	
			xmlhttp.open("POST",site_url("auth_menu_details/auth_menu_details_ajax/delete_auth_menu/"+menu_id+"/"+auth_id),true);
			xmlhttp.send();
			console.log(xmlhttp.status);
			menu_view.innerHTML = "<center><i class=\"loading\"></i></center>";
}

}
function delete_(menu_id)
{
	var result=confirm("Do you really want to delete this Menu detail ?");

		if(result==true)
		{   //var result=confirm("result");
			var menu_view=document.getElementById('menu_view');
			var xmlhttp;
			//confirm(menu_view);
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
			 	xmlhttp=new XMLHttpRequest();
			}
			else
		  	{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
		  	{
		  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
			    {
				    menu_view.innerHTML = xmlhttp.responseText;
			    }
			    //console.log(xmlhttp.status);
		  	}
		  	
			xmlhttp.open("POST",site_url("auth_menu_details/auth_menu_details_ajax/delete_menu/"+menu_id),true);
			xmlhttp.send();
			console.log(xmlhttp.status);
			menu_view.innerHTML = "<center><i class=\"loading\"></i></center>";
}

}
function edit_menu(id,auth_id)
{   //onload_auth('auth_edit');
	// console.log("In function");
	// console.log(id);
	// console.log(auth_id);
	var $row = $('#'+id).closest("tr");
	var $td2 = $row.find("td:nth-child(2)").text();
	var $td3 = $row.find("td:nth-child(3)").text();
	var $td4 = $row.find("td:nth-child(4)").text();
	var $td5 = $row.find("td:nth-child(5)").text();
	var $td6 = $row.find("td:nth-child(6)").text();
	//$('#auth_edit').val();

	//$('.auth_id option[value= '+auth_id+']').attr('selected','selected');
	$('#submenu1_edit').val($td2);
	$('#submenu2_edit').val($td3);
	$('#submenu3_edit').val($td4);
	$('#submenu4_edit').val($td5);
	$('#menulink_edit').val($td6);
	$('#id_edit').val(id);
	//alert($td2);
	
}

function update_rec()
{
	 var submenu1=document.getElementById('submenu1_edit').value;
	var submenu2=document.getElementById('submenu2_edit').value;
	var submenu3=document.getElementById('submenu3_edit').value;
	var submenu4=document.getElementById('submenu4_edit').value;
	var link=document.getElementById('menulink_edit').value;
	 var id=document.getElementById('id_edit').value;
	 var status = document.getElementById('status').value
    
  $("#edit_button").showLoading();
	$.ajax({
		
		//alert(id);
		url:site_url("auth_menu_details/auth_menu_details_ajax/update_auth_menu_fun"),
		type:"post",
		 //dataType:"json",
		 data:{'id' : id,
		 'submenu1':submenu1,
		 'submenu2':submenu2,
		 'submenu3':submenu3,
		 'submenu4':submenu4,
		 'link':link,
		 'status':status},

		success:function(data){
			//alert("Company Rescheduled successfully");
			// console.log(data);
			//alert(data);
			$("#edit_button").hideLoading();
			$('#myModal').modal('hide');
			onchange_authorization();

		},
		

		error:function(error){
			console.log(error);
			alert("ERROR : NOT UPDATED");
			$("#edit_button").hideLoading();
			//location.reload();
		}
	});

}



	
	


// function onchange_authorization()
// 	{
// 		var auth=document.getElementById('auth').value;
// 		var menu_view=document.getElementById('menu_view');
// 		var xmlhttp;
// 		if (window.XMLHttpRequest)
// 		{// code for IE7+, Firefox, Chrome, Opera, Safari
// 		 	xmlhttp=new XMLHttpRequest();
// 		}
// 		else
// 	  	{// code for IE6, IE5
// 			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
// 		}
// 		xmlhttp.onreadystatechange=function()
// 	  	{
// 	  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
// 		    {
// 			    menu_view.innerHTML = xmlhttp.responseText;
// 		    }
// 	  	}
// 		xmlhttp.open("POST",site_url("auth_menu_details/auth_menu_details_ajax/get_auth_menu/"+auth),true);
// 		xmlhttp.send();
// 		menu_view.innerHTML = "<center><i class=\"loading\"></i></center>";
// 	}