$(document).ready(function() {
	if(document.getElementById('student_type').value == 'ug')
		document.getElementById('add').style.display='none';
	else if(document.getElementById("tableid").rows.length > 6)
		document.getElementById('add_new').style.display='none';

	document.getElementById("add").onclick = function() {onclick_add();};

	$('#form_submit').on('submit', function(e) {
		if(!education_validation())
			e.preventDefault();
	});
});

function onclick_add()
{
	var row=document.getElementById("tableid").rows;
	var e=document.getElementsByName("exam4[]")[row.length-2].value;
	var b=document.getElementsByName("branch4[]")[row.length-2].value;
	var c=document.getElementsByName("clgname4[]")[row.length-2].value;
	var g=document.getElementsByName("grade4[]")[row.length-2].value;

	if(e.trim()=="" || b.trim()=="" || c.trim()=="" || g.trim()=="" )
		alert('Sno '+(row.length-1)+' : Please fill up all the fields !!');
	else
	{
		if(row.length > 6)
		{
			alert('You are not allowed to add more rows.');
			return false;
		}
		//onclick_add_row();
		var newrow=document.getElementById("tableid").insertRow(row.length);
		newrow.innerHTML=document.getElementById("addrow").innerHTML;
		var newid=newrow.cells[0].id="sno"+Number(row.length-2);
		document.getElementById(newid).innerHTML=row.length-1;
		document.getElementsByName('branch4[]')[row.length-2].disabled=false;
		var e=document.getElementsByName("exam4[]")[row.length-2].value='';
		var b=document.getElementsByName("branch4[]")[row.length-2].value='';
		var c=document.getElementsByName("clgname4[]")[row.length-2].value='';
		var g=document.getElementsByName("grade4[]")[row.length-2].value='';
	}
}


function onclick_add()
{
        var e=document.getElementsByName("exam4")[0].value;
        var b=document.getElementsByName("branch4")[0].value;
        var c=document.getElementsByName("clgname4")[0].value;
        var y=document.getElementsByName("year4")[0].value;
        var g=document.getElementsByName("grade4")[0].value;
        var d=document.getElementsByName("div4")[0].value;

        if(e=="" || b=="" || c=="" || y=="" || g=="" || d=="" )
                alert('Please fill up all the fields !!');
        else return true;
        return false;
}

function examination_handler()
{
        var exam=document.getElementsByName("exam4")[0].value;
        if(exam=="non-matric")
        {
                document.getElementsByName("branch4")[0].value="n/a";
                document.getElementsByName("clgname4")[0].value="n/a";
                document.getElementsByName("year4")[0].value="n/a";
                document.getElementsByName("grade4")[0].value="n/a";
                document.getElementsByName("div4")[0].value="n/a";
        }
}

function onclick_delete(i)
{
	var result=confirm("Do you really want to delete ?");
	if(result==true)
	{
		var table=document.getElementById('tbl4');
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
		    	$('#show_details').fadeOut('slow',function(){
			    	$(this).removeClass('box-success').removeClass('box-danger').addClass('box-warning').fadeIn('fast');
			    	$('#show_details').find('.box-title').html("Educational Qualifications <label class=\"label label-warning\">Pending for Approval</label>");
			    });
			    table.innerHTML=xmlhttp.responseText;
			    $('#show_details').hideLoading();
		    }
	  	}
	  	xmlhttp.open("POST",site_url("student/student_ajax/delete_record/2/"+i),true);
	  	$('#show_details').showLoading();
		xmlhttp.send();
	}
}

function onclick_edit(i)
{
	var xmlhttp;
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
	 	xmlhttp=new XMLHttpRequest();
	}
	else {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
		    var coverdiv = '<div id="coverdiv" style="height: 100%; width: 100%; top: 0px; left: 0px; opacity: 0.5; position: fixed; z-index: 2000; background: rgb(170, 170, 170);"></div>';
			var formdiv = '<div id="formdiv" style="height: auto; width: auto; top: 10%; left: 15%; right:15%; display: block; position: absolute; z-index: 2001; background: #FEFEFE;">';
			formdiv+=xmlhttp.responseText+'</div>';
			var div = document.createElement('div');
			div.setAttribute("id", "edit_div");
			div.innerHTML=coverdiv+formdiv;
			document.body.appendChild(div);
	    }
  	}
  	xmlhttp.open("POST",site_url("student/student_ajax/edit_record/2/"+i),true);
	xmlhttp.send();
}

function onclick_save(i)
{
	var e=document.getElementById("edit_exam"+i).value;
	var b=document.getElementById("edit_branch"+i).value;
	var c=document.getElementById("edit_clgname"+i).value;
	var y=document.getElementById("edit_year"+i).value;
	var g=document.getElementById("edit_grade"+i).value;
	var d=document.getElementById("edit_div"+i).value;

	if(e=="" || b=="" || c=="" || y=="" || g=="" || d=="" )
		alert("!! Please fill up all the fields !!");
	else
	{
		return true;
	}
	return false
}

function examination_editbtn_handler(i)
{
	var exam=document.getElementById("edit_exam"+i).value;
	if(exam=="non-matric")
	{
		document.getElementById("edit_branch"+i).value="n/a";
		document.getElementById("edit_clgname"+i).value="n/a";
		document.getElementById("edit_year"+i).value="n/a";
		document.getElementById("edit_grade"+i).value="n/a";
		document.getElementById("edit_div"+i).value="n/a";
	}
}

function closeframe()
{
	$('#edit_div').remove();
}

$(document).ready(function() {
        $("#add_btn").click(function(e) {
                if(!onclick_add())
                        e.preventDefault();
        });

        $("select[name=exam4]").change(examination_handler);

        $("#back_btn").click(function(e) {
                window.location.href = site_url("student/student_edit");
        });
});


function education_validation()
{
	var n_row=document.getElementById("tableid").rows.length;
	var i=0;
	for(i=0;i<=n_row-2;i++)
	{
		var e=document.getElementsByName("exam4[]")[i].value;
		var b=document.getElementsByName("branch4[]")[i].value;
		var c=document.getElementsByName("clgname4[]")[i].value;
		var g=document.getElementsByName("grade4[]")[i].value;
			
		if(e.trim()=="" || b.trim()=="" || c.trim()=="" || g.trim()=="" )
		{
			alert('Sno '+(i+1)+': Please fill up all the fields !!');
			return false;
		}
	}
	return true;
}