        $(document).ready(function() {
				
				$("#data_dialog_fade").modal({backdrop: "static"});
				
                if(stu_type == 'ug')
                        document.getElementById('add').style.display='none';
                else if(document.getElementById("tableid").rows.length > 6)
                        document.getElementById('add').style.display='none';
						
				if(document.getElementById("tableid").rows.length <= 3)
						document.getElementById('remove').style.display='none';

                document.getElementById("add").onclick = function() {onclick_add();};
				document.getElementById("remove").onclick = function() {onclick_remove();};
							
                if(document.getElementById('roll_no').value == 'na')
                        document.getElementById('roll_no').value = '';
                corrAddr();
				

                $('#form_submit').on('submit', function(e) {
                        if(!form_validation())
                                e.preventDefault();
                });
				
                $('#correspondence_addr').on('ifChanged', function() {
                        corrAddr();
                });
				
				$('#payment_mode').on('change', function() {
					
					if($('#payment_mode').val() == 'challan')
					{
						document.getElementById('fee_paid_dd_chk_onlinetransaction_cashreceipt_no').value = "0";
						document.getElementById('fee_paid_dd_chk_onlinetransaction_cashreceipt_no').disabled = true;
					}
					else
					{
						document.getElementById('fee_paid_dd_chk_onlinetransaction_cashreceipt_no').disabled = false;
						if( $('#fee_paid_dd_chk_onlinetransaction_cashreceipt_no').val() == '0')
						{	
							document.getElementById('fee_paid_dd_chk_onlinetransaction_cashreceipt_no').value = "";
						}
					}
					
					});

                $('[name="depends_on"]').on('ifChanged', function() {
                depends_on_whom();
                });

        });
		
		function onclick_remove()
		{
			var table=document.getElementById("tableid");
			var rowCount=table.rows.length;
			if(rowCount<=2)
			{
				alert("Cannot delete any more rows.");
				return;
			}
			table.deleteRow(rowCount-1);
			button_for_remove();
		}
	
	
		function button_for_remove()
		{
			var row=document.getElementById("tableid").rows;
			if(row.length > 3)
				document.getElementById('remove').style.display='block';
			else
				document.getElementById('remove').style.display='none';
		}


        function form_validation()
        {
                if(!parent_guardian_validation())
                        return false;
                if(!correspondence_addr_validation())
                        return false;
                if(!all_number_validation())
                        return false;
                if(!mobile_number_size_validation())
                        return false;
                if(!education_validation())
                        return false;
				if(!validFile())
						return false;
                //push_na_in_empty();
				//alert("Submit");
				//return false;
                return true;
        }

        function correspondence_addr_validation()
        {
                var ca=document.getElementById("correspondence_addr").checked;
                if(ca)
                        return true;
                else
                {
					alert("start");
                        var line1 = document.getElementById("line13").value;
                        var line2 = document.getElementById("line23").value;
                        var city = document.getElementById("city3").value;
                        var state = document.getElementById("state3").value;
                        var pincode = document.getElementById("pincode3").value;
                        var country = document.getElementById("country3").value;
                        var contact = document.getElementById("contact3").value;
						alert("corr");
                        if(line1.trim() == '' || city.trim() =='' || pincode.trim() == '' || state.trim() == '' || country.trim() == ''|| contact.trim() == '')
                        {
                                alert("Please fill all the fields of correspondence address.");
                                return false;
                        }
                        else if(isNaN(pincode))
                        {
                                alert("Pincode can contain only numbers.");
                                return false;
                        }
                        if(isNaN(contact))
                        {
                                alert("Correspondance Contact can contain only numbers.");
                                return false;
                        }
                        else if(contact >= 10000000000 || contact < 1000000000)
                        {
                                alert("Correspondence mobile number not in range.");
                                return false;
                        }
                        return true;
                }
        }

        function parent_guardian_validation()
        {
                var dpe = document.getElementById("depends_on").checked;

                if(!dpe)
                {
                        if(mname)	var m=document.getElementById("mother_name").value;
                        if(fname)	var f= document.getElementById("father_name").value;
                        var fo=document.getElementById("father_occupation").value;
                        var mo=document.getElementById("mother_occupation").value;
                        var fgai=document.getElementById("father_gross_income").value;
                        var mgai=document.getElementById("mother_gross_income").value;
                        if( fo.trim() == '' || mo.trim() == '' || fgai.trim() == '' || mgai.trim() == '')
                        {
							if((fname	&&	f.trim()=='')	||	(mname	&&	m.trim()==''))
                                alert("Please fill all details of parents.")
                                return false;
                        }
                        else
                                return true;
                }
                else
                {
                        var g=document.getElementById("guardian_name").value;
                        var r=document.getElementById("guardian_relation_name").value;
                        if(g.trim() == '' || r.trim() == '')
                        {
                                alert("Please fill all details of guardian.")
                                return false;
                        }
                        else
                                return true;
                }
        }
		
        function corrAddr()
        {
                var y=document.getElementById("correspondence_addr");
                if(y.checked)
                {
                        document.getElementById('corr_addr_visibility').style.display = 'none';
                }
                else
                {
                        document.getElementById('corr_addr_visibility').style.display = 'block';
                }
        }

        function corrAddr1()
        {
                var y=document.getElementById("correspondence_addr");
                if(y.checked)
                {
                        document.getElementById('corr_addr_visibility').style.display = 'none';
                }
                else
                {
                        document.getElementById('corr_addr_visibility').style.display = 'block';
                }
        }

        function depends_on_whom()
        {
                var dpe = document.getElementById("depends_on").checked;

                if(mname)	var m=document.getElementById("mother_name");
                if(fname)	var f= document.getElementById("father_name");
                var g=document.getElementById("guardian_name");
                var r=document.getElementById("guardian_relation_name");
                var fo=document.getElementById("father_occupation");
                var mo=document.getElementById("mother_occupation");
                var fgai=document.getElementById("father_gross_income");
                var mgai=document.getElementById("mother_gross_income");

                if(dpe)
                {
					m.value="";
					f.value="";
					mo.value="";
					mo.value="";
					mgai.value="";
					fgai.value="";
                        if(mname)	m.disabled=true;
                        if(fname)	f.disabled=true;
                        g.disabled=false;
                        r.disabled=false;
                        fo.disabled=true;
                        mo.disabled=true;
                        fgai.disabled=true;
                        mgai.disabled=true;
                }
                else
                {
                        if(mname)	m.disabled=false;
                        if(fname)	f.disabled=false;
						g.value="";
						r.value="";
                        g.disabled=true;
                        r.disabled=true;
                        fo.disabled=false;
                        mo.disabled=false;
                        fgai.disabled=false;
                        mgai.disabled=false;
                }

        }

        function depends_on_whom1()
        {
                var m=document.getElementById("mother_name");
                var f= document.getElementById("father_name");
                var g=document.getElementById("guardian_name");
                var r=document.getElementById("guardian_relation_name");
                var fo=document.getElementById("father_occupation");
                var mo=document.getElementById("mother_occupation");
                var fgai=document.getElementById("father_gross_income");
                var mgai=document.getElementById("mother_gross_income");

                        m.disabled=true;
                        f.disabled=true;
                        g.disabled=false;
                        r.disabled=false;
                        fo.disabled=true;
                        mo.disabled=true;
                        fgai.disabled=true;
                        mgai.disabled=true;

        }

    function all_number_validation()
        {
                if(isNaN(document.getElementById('father_gross_income').value))
                {
                        alert("Father's Gross Income can only contain digits.");
                        return false;
                }
                if(isNaN(document.getElementById('mother_gross_income').value))
                {
                        alert("Mother's Gross Income can only contain digits.");
                        return false;
                }
                if(isNaN(document.getElementById('parent_mobile').value))
                {
                        alert("Parent Mobile number can contain only digits.");
                        return false;
                }
                if(isNaN(document.getElementById('parent_landline').value))
                {
                        alert("Parent Landline number can only contain digits.");
                        return false;
                }
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
                if(isNaN(document.getElementById('contact1').value))
                {
                        alert("Contact of present address can contain only digits.");
                        return false;
                }
                if(isNaN(document.getElementById('contact2').value))
                {
                        alert("Contact of permanent address can contain only digits.");
                        return false;
                }
                if(isNaN(document.getElementById('fee_paid_amount').value))
                {
                        alert("Fee Paid Amount field can contain only digits.");
                        return false;
                }
                /*if(isNaN(document.getElementById('iitjee_cat_rank').value) || isNaN(document.getElementById('iitjee_rank').value))
                {
                        alert("Rank can only contain digits.")
                        return false;
                }*/
                return true;
        }

        function mobile_number_size_validation()
        {
                var parent_mobile_no = document.getElementById('parent_mobile').value;
                var present_contact_no = document.getElementById('contact1').value;
                var permanent_contact_no = document.getElementById('contact2').value;
                var correspondence_contact_no = document.getElementById('contact3').value;
                //var mobile_no = document.getElementById('mobile').value;
                //var alternate_mobile_no = document.getElementById('alternate_mobile').value;
                if(parent_mobile_no >= 10000000000 || parent_mobile_no < 1000000000)
                {
                        alert("Parent mobile number not in range");
                        return false;
                }
				/*else if(parent_mobile_no.toString().length != 10)
                {
                        alert("Parent mobile number not in range");
                        return false;
                }*/
                else if(present_contact_no >= 10000000000 || present_contact_no < 1000000000)
                {
                        alert("Present address mobile number not in range");
                        return false;
                }
				/*else if(present_contact_no.toString().length != 10)
                {
                        alert("Present address mobile number not in range");
                        return false;
                }*/
                else if(permanent_contact_no >= 10000000000 || permanent_contact_no < 1000000000)
                {
                        alert("Permanent address mobile number not in range");
                        return false;
                }
				/*else if(permanent_contact_no.toString().length != 10)
                {
                        alert("Permanent address mobile number not in range");
                        return false;
                }*/
                // else if(mobile_no >= 10000000000 || mobile_no < 1000000000)
                // {
                //      alert("Your mobile number not in range");
                //      return false;
                // }
                // else if(alternate_mobile_no != '' && (alternate_mobile_no >= 10000000000 || alternate_mobile_no < 1000000000))
                // {
                //      alert("Your alternate mobile number not in range");
                //      return false;
                // }
                return true;
        }

        function push_na_in_empty()
        {
/*                if( document.getElementById('middlename').value.trim() == '')
                        document.getElementById('middlename').value = 'na';
                if( document.getElementById('lastname').value.trim() == '')
                        document.getElementById('lastname').value = 'na';
*/                if( document.getElementById('roll_no').value.trim() == '')
                        document.getElementById('roll_no').value = 'na';
                if( document.getElementById('parent_landline').value.trim() == '')
                        document.getElementById('parent_landline').value = 0;
                if( document.getElementById('aadhaar_no').value.trim() == '')
                        document.getElementById('aadhaar_no').value = 'na';
                if( document.getElementById('fee_paid_dd_chk_onlinetransaction_cashreceipt_no').value.trim() == '')
                        document.getElementById('fee_paid_dd_chk_onlinetransaction_cashreceipt_no').value = 'na';
                if( document.getElementById('fee_paid_amount').value.trim() == '')
                        document.getElementById('fee_paid_amount').value = 0;
        }

function onclick_add()
{
        var row=document.getElementById("tableid").rows;
        /*var e=document.getElementsByName("exam4[]")[row.length-2].value;
        var b=document.getElementsByName("branch4[]")[row.length-2].value;
        var c=document.getElementsByName("clgname4[]")[row.length-2].value;
        var g=document.getElementsByName("grade4[]")[row.length-2].value;

        if(e.trim()=="" || b.trim()=="" || c.trim()=="" || g.trim()=="" )
                alert('Sno '+(row.length-1)+' : Please fill up all the fields !!');
        else*/
        if(education_validation())
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

function getExtension(filename) {
    var parts = filename.split('.');
    return parts[parts.length - 1];
}

function checkPhoto(filename) {
    var ext = getExtension(filename);
    switch (ext.toLowerCase()) {
    case 'jpg':
    case 'jpeg':
    case 'png':
        return true;
    }
    return false;
}

function checkSlip(filename) {
    var ext = getExtension(filename);
    switch (ext.toLowerCase()) {
    case 'jpg':
    case 'jpeg':
    case 'png':
	case 'pdf':
        return true;
    }
    return false;
}

function validSize(fieldId)
{
	if(document.getElementById(fieldId).files[0].size > 204800)
		return false;
		
	return true;
}

function validFile() {
	var img = document.getElementById('photo');
	var slip = document.getElementById('slip');
	//alert("Fv");
	if(img.value === "")
	{
		alert("Please upload your photo.");
		return false;
	}
	else if(slip.value === "")
	{
		alert("Please upload the fee reciept.");
		return false;
	}
	else if(!checkPhoto(img.value))
	{
		alert("Please upload a photo of valid size and extension.\nValid Size: 200KB\nValid Extensions: \"JPG/JPEG/PNG\" ");
		return false;
	}
	else if(!checkSlip(slip.value))
	{
		alert("Please upload a Fee Reciept file of valid size and extension.\nValid Size: 200KB\nValid Extensions: \"JPG/JPEG/PNG/PDF\" ");
		return false;
	}
	else if(!validSize('photo'))
	{
		alert("Please upload a photo of valid size.\nValid Size: 200KB.");
		return false;
	}
	else if(!validSize('slip'))
	{
		alert("Please upload a fee reciept of valid size.\nValid Size: 200KB.");
		return false;
	}

	return true;	
}

function preview_pic()
{
        var file=document.getElementById('photo').files[0];
        if(!file)
                alert("!! Select a file first !!");
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