function getxmlhttp()
{
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
    }
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
}
function get_publication_type(type)
{
	var xmlhttp = getxmlhttp();
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status==200)
		{
			document.getElementById("pub_type").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("POST",site_url("publication/add_publication_ajax/get_type/"+type),true);
	xmlhttp.send();
	return false;
}
function get_authors(type)
{
	if(type !== ''){
		var xmlhttp = getxmlhttp();
		xmlhttp.onreadystatechange = function()
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status==200)
			{
				document.getElementById("num_author").innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.open("POST",site_url("publication/add_publication_ajax/add_authors/"+type),true);
		xmlhttp.send();
		return false;
	}
	else{
		document.getElementById("num_author").innerHTML = '';
	}
}
function add_template(type,type1)
{
	var xmlhttp = getxmlhttp();
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status==200)
		{
			document.getElementById("other_author"+type1).innerHTML = xmlhttp.responseText;

			var xmlhttp_ = getxmlhttp();
			xmlhttp_.onreadystatechange = function()
			{
				if (xmlhttp_.readyState == 4 && xmlhttp_.status==200)
				{
					document.getElementById("department_name"+type1).innerHTML = xmlhttp_.responseText;
				}
			}
			xmlhttp_.open("POST",site_url("publication/add_publication_ajax/find_department/"+type+"/"+type1),true);
			xmlhttp_.send();
			
		}
	}
	xmlhttp.open("POST",site_url("publication/add_publication_ajax/input_authors/"+type+"/"+type1),true);
	xmlhttp.send();
	return false;
}
function find_faculty(type,type1)
{
	var xmlhttp = getxmlhttp();
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status==200)
		{
			document.getElementById("author_"+type1+"_emp_id").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("POST",site_url("publication/add_publication_ajax/find_faculty/"+type),true);
	xmlhttp.send();
	return false;
}

function find_faculty_query(type)
{
	var xmlhttp = getxmlhttp();
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status==200)
		{
			document.getElementById("faculty_name").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("POST",site_url("publication/add_publication_ajax/find_faculty_for_query/"+type),true);
	xmlhttp.send();
	return false;
}

function get_dept(type)
{
	var xmlhttp = getxmlhttp();
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status==200)
		{
			document.getElementById("department_name").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("POST",site_url("publication/search_publication_ajax/find_department/"+type),true);
	xmlhttp.send();
	return false;
}

function get_dept_query(type)
{
	var xmlhttp = getxmlhttp();
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status==200)
		{
			document.getElementById("department_name").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("POST",site_url("publication/search_publication_ajax/find_department_query/"+type),true);
	xmlhttp.send();
	return false;
}

function put_year()
{
	var xmlhttp = getxmlhttp();
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status==200)
		{
			document.getElementById("year").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("POST",site_url("publication/add_publication_ajax/put_year/"),true);
	xmlhttp.send();
	return false;
}
function author_type(value,id)
{
	var xmlhttp = getxmlhttp();
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status==200)
		{
			document.getElementById("other_author"+id).innerHTML = xmlhttp.responseText;
		}
	}
	if (value == 'ISM')
		xmlhttp.open("POST",site_url("publication/add_publication_ajax/add_ism_authors/"+id),true);
	else
		xmlhttp.open("POST",site_url("publication/add_publication_ajax/add_ism_authors/"+id),true);
	xmlhttp.send();
	return false;
}
function select_branch(ism_author_type,author_no)
{
	var xmlhttp = getxmlhttp();
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status==200)
		{
			document.getElementById("branch"+author_no).innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("POST",site_url("publication/add_publication_ajax/select_branch/"+ism_author_type+"/"+author_no),true);
	xmlhttp.send();
	return false;
}
function get_user_by_branch(dept , auth_no){
		var id = '#co_author_type'+auth_no;
		var type_of_author = $(id).val();
		var id2 = '#id'+auth_no;
		// alert(id+" "+type_of_author+" "+id2);
		$.ajax({
			url : site_url('publication/add_publication_ajax/get_user_by_branch/'+dept+"/"+type_of_author),
			success : function(result){
				$(id2).html(result);
			},
			error : function(err){
				$id2.html(result.responseText);
			}
		});
}

function other_author(author_no){
		var id = '#other_author'+author_no;
		$.ajax({
			url : site_url('publication/add_publication_ajax/other_author/'+author_no),
			success : function(result){
				$(id).html(result);
			}
		});
}