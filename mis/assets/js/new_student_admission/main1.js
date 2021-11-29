function showUser() {
	var str=document.getElementById("Reg_id").value;
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "*Please fill the field.";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","student_register_deo_jee/temp_data?q="+str,true);
        xmlhttp.send();
    }
}

function showUserJrf() {
	var str=document.getElementById("Reg_id").value;
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "*Please fill the field.";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","student_register_deo_jrf/temp_data?q="+str,true);
        xmlhttp.send();
    }
}

function showUserMtech() {
	var str=document.getElementById("Reg_id").value;
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "*Please fill the field.";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","student_register_deo_mtech/temp_data?q="+str,true);
        xmlhttp.send();
    }
}
function showUserMtech3years() {
	var str=document.getElementById("Reg_id").value;
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "*Please fill the field.";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","student_register_deo_mtech_3years/temp_data?q="+str,true);
        xmlhttp.send();
    }
}

function showUserMba() {
	var str=document.getElementById("Reg_id").value;
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "*Please fill the field.";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","student_register_deo_mba/temp_data?q="+str,true);
        xmlhttp.send();
    }
}
function showUserExecMba() {
	var str=document.getElementById("Reg_id").value;
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "*Please fill the field.";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","student_register_deo_execmba/temp_data?q="+str,true);
        xmlhttp.send();
    }
}


function showUserMsc() {
	var str=document.getElementById("Reg_id").value;
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "*Please fill the field.";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","student_register_deo_msc/temp_data?q="+str,true);
        xmlhttp.send();
    }
}

function showUserMscTech() {
	var str=document.getElementById("Reg_id12").value;
    if (str == "") {
        document.getElementById("txtHint12").innerHTML = "*Please fill the field.";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint12").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","student_register_deo_msc_tech/temp_data?q="+str,true);
        xmlhttp.send();
    }
}


	
function showDept(str) {
	
    if (str == "") {
        document.getElementById("dept_name").innerHTML = "*Please fill the field.";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("dept_name").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","student_register_deo_mtech/get_dept_by_branch_id?q="+str,true);
        xmlhttp.send();
    }	
}

