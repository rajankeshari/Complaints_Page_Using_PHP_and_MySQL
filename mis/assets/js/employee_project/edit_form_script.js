 $(document).ready(function() {

				if(document.getElementById("tableid").rows.length <= 2)
						document.getElementById('remove').style.display='none';

                document.getElementById("add").onclick = function() {onclick_add();};
				document.getElementById("remove").onclick = function() {onclick_remove();};

				$('#form_submit').on('submit', function(e) {
                       if(!form_validation())
                                e.preventDefault();
                });
 });

 		function form_validation()
        {

				if(document.getElementById("photo").files.length != 0 && !image_validation())
						return false;
				if(document.getElementById("idProof").files.length != 0 && !idProof_validation())
						return false;
				if(!education_validation())
                        return false;
				if(!select_validation())
						return false;
				if(!all_number_validation())
                        return false;
				if(!contact_number_size_validation())
						return false;

                //push_na_in_empty();
                return true;

        }

		function select_validation()
		{
			if(document.getElementById('depts').value == "0")
					{
						alert("Please select a department");
						return false;
					}

			if(document.getElementById('state1').value == "0")
					{
						alert("Please select a state present address state");
						return false;
					}

			if(document.getElementById('state2').value == "0")
					{
						alert("Please select a permanent address state");
						return false;
					}
			return true;
		}

		 function all_number_validation()
        {

                if(isNaN(document.getElementById('pincode1').value))
                {
                        alert("Pincode of present address can only contain digits.");
                        return false;
                }
                if(isNaN(document.getElementById('pincode2').value))
                {
                        alert("Pincode of premanent address can only contain digits.");
                        return false;
                }
                if(isNaN(document.getElementById('contact11').value))
                {
                        alert("Contact of present address can contain only digits.");
                        return false;
                }
                if(isNaN(document.getElementById('contact12').value))
                {
                        alert("Contact of permanent address can contain only digits.");
                        return false;
                }
                return true;
        }

        function contact_number_size_validation()
        {
                var present_contact_no = document.getElementById('contact11').value;
                var permanent_contact_no = document.getElementById('contact12').value;

                if(present_contact_no >= 10000000000 || present_contact_no < 1000000000)
                {
                        alert("Present address mobile number not in range");
                        return false;
                }
                else if(permanent_contact_no >= 10000000000 || permanent_contact_no < 1000000000)
                {
                        alert("Permanent address mobile number not in range");
                        return false;
                }

                return true;
        }

	function onclick_remove()
		{
			var table=document.getElementById("tableid");
			var rowCount=table.rows.length;
			if(rowCount<=1)
			{
				alert("Cannot delete any more rows.");
				return;
			}
			table.deleteRow(rowCount-1);
			button_for_remove();
		}

	function onclick_add()
	{
        var row=document.getElementById("tableid").rows;

        if(add_education_validation())
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
				button_for_remove();
        }
	}

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

               if( (e.trim()=="" || b.trim()=="" || c.trim()=="" || g.trim()=="") && !(e.trim()=="" && b.trim()=="" && c.trim()=="" && g.trim()=="" ) )
                {
                        alert('Sno '+(i+1)+': Please fill up all the fields !!');
                        return false;
                }
        }


        return true;
	}

	function add_education_validation()
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

	function button_for_add()
	{
	        if(document.getElementById('stu_type').value == 'ug')
        	{
                document.getElementById('add').style.display='none';
    	    }
	        else
        	{
                document.getElementById('add').style.display='block';
    	    }
	}

	function button_for_remove()
		{
			var row=document.getElementById("tableid").rows;
			if(row.length > 2)
				document.getElementById('remove').style.display='block';
			else
				document.getElementById('remove').style.display='none';
		}

	function preview_pic() {
		var file=document.getElementById('photo').files[0];
		if(!file)
			document.getElementById('view_photo').src =  base_url()+"assets/images/employee/noProfileImage.png";
      	else
		{
			oFReader = new FileReader();
        	oFReader.onload = function(oFREvent)
			{
				var dataURI = oFREvent.target.result;
				document.getElementById('view_photo').src = dataURI;
			};
			oFReader.readAsDataURL(file);
		}
	}

	function image_validation()
	{
		var file=document.getElementById('photo').files[0];
		var ext=file.name.substring(file.name.lastIndexOf('.') + 1);
		if(ext == "bmp" || ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg" )
		{
			if(file.size>204800)
			{
				alert('The file size must be less than 200KB');
				return false;
			}
			else
				return true;
		}
		else
		{
			alert('The image should be in bmp, gif, png, jpg or jpeg format.');
			return false;
		}
	}

	function idProof_validation()
	{
		var file=document.getElementById('idProof').files[0];
		var ext=file.name.substring(file.name.lastIndexOf('.') + 1);
		if(ext == "bmp" || ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg" )
		{
			if(file.size>204800)
			{
				alert('The file size must be less than 200KB');
				return false;
			}
			else
				return true;
		}
		else
		{
			alert('The Identity Proof should be in bmp, gif, png, jpg or jpeg format.');
			return false;
		}
	}



	$(document).ready(function() {
	//	$(".hideit, #empIdIcon, #emp_id_display").hide();
		$("#basic_details").on('submit',function(e) {
			if(!image_validation() && document.getElementById("photo").files.length != 0)
				e.preventDefault();
		});

		$("#dob").datepicker("setEndDate", moment($("#dob").attr('max'), "DD-MM-YYYY").toDate());

	});
